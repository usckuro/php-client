<?php

require_once __DIR__ . '/../vendor/autoload.php';
use Httpful\Request;

/**
 * The base API class for the Interqualitas API Wrapper
 * @author Jon Wadsworth <jon@interqualitas.net>
 */
class Interqualitas {
    
    /**
     *
     * @var string $username The username to be used to connect to the API
     */
    protected $username;
    
    /**
     *
     * @var string $authCode The auth code provided by interqualitas
     */
    protected $authCode;
    
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
     * @var string $token The token provided for this session
     */
    private $token;
    
    public function __construct($username, $authCode, $clientSecret, $endPoint = 'https://interqualitas.net') {
        $this->username = $username;
        $this->authCode = $authCode;
        $this->clientSecret = $clientSecret;
        $this->endPoint = $endPoint;
        $this->authenticate();
    }
    
    /**
     * 
     */
    public function authenticate() {
        $request = Request::post($this->endPoint . '/oauth', 
            json_encode([
                'redirect_uri'  => '/oauth/receivecode',
                'client_id'     => $this->username,
                'client_secret' => $this->clientSecret,
                'code'          => $this->authCode,
                'grant_type'    => 'authorization_code'
            ]));
        $response = $request->send();
        print_r($response);
    }
}