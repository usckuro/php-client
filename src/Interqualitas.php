<?php

//Autoloading
$autoloadFiles = array(
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../../autoload.php');
foreach ($autoloadFiles as $autoloadFile) {
    if (file_exists($autoloadFile)) {
        require_once $autoloadFile;
    }
}
//Abstracts
require_once 'Interqualitas/ModuleAbstract.php';
//End Points
require_once 'Interqualitas/Geo.php';
require_once 'Interqualitas/Policy.php';
require_once 'Interqualitas/PolicyFactorLevel.php';
require_once 'Interqualitas/PolicyHolder.php';
require_once 'Interqualitas/PolicyPackage.php';
require_once 'Interqualitas/Vehicle.php';
require_once 'Interqualitas/VehicleMake.php';
require_once 'Interqualitas/VehicleValuation.php';
//Exception Classes
require_once 'Interqualitas/Exception/AuthenticationFailed.php';
use Httpful\Request;
use Httpful\Response;

/**
 * The base API class for the Interqualitas API Wrapper
 *
 * @author Jon Wadsworth <jon@interqualitas.net>
 */
class Interqualitas {

    //HTTP Method Constants
    const METHOD_GET = 1;
    const METHOD_POST = 2;
    const METHOD_PATCH = 3;
    const METHOD_DELETE = 4;

    //Service Endpoint constants
    const PRODUCTION = 'https://www.interqualitas.net';
    const SANDBOX = 'http://sandbox.interqualitas.net';

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

    /**
     * @var string Stores any authentication message received from server
     */
    private $authenticationMessage = '';

    /**
     *
     * Creates a new instance of this wrapper and attempts to authenticate with given credentials
     *
     * @param string $username The username given to client
     * @param string $clientSecret The client secret provided to client
     * @param string $endPoint The endpoint to use SANDBOX|PRODUCTION
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
     * Makes a call to the API server and returns the response
     *
     * @param string $modulePath The module path (generally provided by the API Class)
     * @param string $id The id for a get method
     * @param array  $params Any params that need to be added used for creating, editing, or filtering data
     * @param int    $method The HTTP method to be used
     * @return mixed The result of the call
     */
    public function makeCall($modulePath, $id = '', $params = [], $method = self::METHOD_GET) {
        $uri = $this->endPoint . '/' . $modulePath . (!empty(trim($id)) ? ('/' . $id) : '');
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
        if ($method == self::METHOD_GET || $method == self::METHOD_DELETE) {
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
     * Authenticates with the data given on construct
     */
    public function authenticate() {
        //Forming authentication request
        $request = Request::post($this->endPoint . '/oauth')
            ->authenticateWithBasic($this->username, $this->clientSecret)
            ->body(json_encode([
                'username'   => $this->username,
                'password'   => $this->clientSecret,
                'grant_type' => 'client_credentials']))
            ->sendsJson();
        $response = $request->send();
        //Digesting request
        if ($response->code === 200) {
            $body = $response->body;
            if (isset($body->access_token)) {
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

    /**
     * Gets the authentication method in case of failure
     *
     * @return string The authentication method
     */
    public function getAuthenticationMessage() {
        return $this->authenticationMessage;
    }

    /**
     * Converts the object to an array
     *
     * @return array The object returned as an array
     */
    public function toArray() {
        return [
            'token'     => $this->token,
            'lifeSpan'  => $this->tokenLifeSpan,
            'timestamp' => $this->tokenTimeStamp
        ];
    }

    /**
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * @param string $clientSecret
     */
    public function setClientSecret($clientSecret) {
        $this->clientSecret = $clientSecret;
    }

    /**
     * @return string
     */
    public function getEndPoint() {
        return $this->endPoint;
    }

    /**
     * @param string $endPoint
     */
    public function setEndPoint($endPoint) {
        $this->endPoint = $endPoint;
    }
}