<?php

$autoloadFiles = array(
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../../autoload.php');

foreach ($autoloadFiles as $autoloadFile) {
    if (file_exists($autoloadFile)) {
        require_once $autoloadFile;
    }
}

require_once 'Interqualitas/ModuleAbstract.php';

require_once 'Interqualitas/Geo.php';
require_once 'Interqualitas/Policy.php';
require_once 'Interqualitas/PolicyHolder.php';
require_once 'Interqualitas/PolicyPackage.php';
require_once 'Interqualitas/Vehicle.php';
require_once 'Interqualitas/VehicleMake.php';
require_once 'Interqualitas/VehicleValuation.php';

use Httpful\Request;
use Httpful\Response;

/**
 * The base API class for the Interqualitas API Wrapper
 * @author Jon Wadsworth <jon@interqualitas.net>
 */
class Interqualitas {
    
    //HTTP Method Constants
    const METHOD_GET    = 1;
    const METHOD_POST   = 2;
    const METHOD_PATCH  = 3;
    const METHOD_DELETE = 4;

    //End point description
    const PRODUCTION    = 'https://www.interqualitas.net';
    const SANDBOX       = 'https://sandbox.interqualitas.net';
    
    /**
     *
     * @var string $username The username to be used to connect to the API
     */
    protected $username;
    
    
    /**
     *
     * @var string $clientSecret The secret that was provided by the user
     */
    protected $clientSecret;
    
    /**
     *
     * @var string $endPoint The base endpoint to be used for connections.
     */
    protected $endPoint;
    
    /**
     *
     * @var integer $tokenTimeStamp The time stamp when the authentication was completed
     */
    protected $tokenTimeStamp;
    
    /**
     *
     * @var integer $tokenLifeSpan When the token will expire
     */
    protected $tokenLifeSpan = 3600;
    
    /**
     *
     * @var string $token The token provided for this session
     */
    private $token;
    
    private $authenticationMessage = '';

    /**
     *
     * Instantiates a new Interqualitas object and authenticates with given credentials
     * @param string $username The username of the accessing user
     * @param string $clientSecret The given client credential
     * @param string $endPoint The service endpoint either Interqualitas::SANDBOX|Interqualitas::PRODUCTION.  Sandbox is assumed
     * @throws \Interqualitas\Exception\AuthenticationFailed
     */
    public function __construct($username, $clientSecret, $endPoint = self::SANDBOX) {
        $this->username = $username;
        $this->clientSecret = $clientSecret;
        $this->endPoint = $endPoint;
        $this->authenticate();
    }

    /**
     *
     * Makes a call to the InterQualitas API Service
     * @param string $modulePath The path for the module ie api/policy
     * @param string $id The id to be used in a get transaction
     * @param array  $params The params to be sent for the call
     * @param int    $method The method to be used GET|POST|PATCH|DELETE
     * @return mixed
     */
    public function makeCall($modulePath, $id = '', $params = [], $method = self::METHOD_GET) {
        $uri = $this->endPoint . '/' . $modulePath . (!empty(trim($id))?('/' . $id):'');
        
        //Setup Request Object
        switch ($method) {
            case self::METHOD_GET:
                $request = Request::get($uri)->sendsJson();
                break;
            case self::METHOD_POST:
                $request = Request::post($uri)->sendsJson();
                break;
            case self::METHOD_PATCH:
                $request = Request::patch($uri)->sendsJson();
                break;
            case self::METHOD_DELETE:
                $request = Request::delete($uri)->sendsJson();
                break;
            default:
                $request = Request::get($uri)->sendsJson();
                break;
        }
        
        $request->expectsJson();
        //Handle Data
        if($method == self::METHOD_GET || $method == self::METHOD_DELETE) {
            $params['access_token'] = $this->token;
            $request->uri .= '?' . http_build_query($params);
        }
        else {
            $request->uri .= '?access_token=' . $this->token;
            $request->body(json_encode($params));
        }
                
        return $request->send();
    }

    /**
     *
     * Calls the provided url after adding authentication information
     * @param $url The direct URL to call
     * @return mixed
     */
    public function makeRawCall($url) {
        if(strpos($url, '?') !== false){
            $url .= '&access_token=' . $this->token;
        }
        else {
            $url .= '?access_token=' . $this->token;
        }
        return Request::get($url)
            ->sendsJson()
            ->expectsJson()
            ->send();
    }

    /**
     *
     * Authenticates with the InterQualitas API Service
     * @return bool
     * @throws \Interqualitas\Exception\AuthenticationFailed
     */
    public function authenticate() {
        //Forming authentication request
        $request = Request::post($this->endPoint . '/oauth')
            ->authenticateWithBasic($this->username, $this->clientSecret)
            ->body(json_encode([
                'username'      => $this->username,
                'password'      => $this->clientSecret,
                'grant_type'    =>'client_credentials']))
            ->sendsJson();
        
        $response = $request->send();
        
        //Digesting request
        if($response->code === 200) {
            $body = $response->body;
            if(isset($body->access_token)){
                $this->token = $body->access_token;
                $this->tokenLifeSpan = $body->expires_in;
                $this->tokenTimeStamp = time();
                return true;
            }
            else {
                $this->authenticationMessage = $response->body;
                return false;
            }
        }
        else {
                throw new \Interqualitas\Exception\AuthenticationFailed($response->body->detail);
        }
    }
    
    public function getAuthenticationMessage() {
        return $this->authenticationMessage;
    }
    
    public function toArray() {
        return [
            'token'     => $this->token,
            'lifeSpan'  => $this->tokenLifeSpan,
            'timestamp' => $this->tokenTimeStamp
        ];
    }
}