The Interqualitas PHP Client provides access to the Interqualitas API client and allows easy access to all functionality of the API.

If you know how to handle installation and usage of composer modules, you can skip the rest of this.  A quick example of the functions can be found under
    docs/example/full

Full documentation of the API is at the Interqualitas developer [website](http://dev.interqualitas.net)

##Requirements
  * PHP >= 5.3
  * PHP cURL extension

##Instalation
To install Interqualitas PHP Client into your project we recommend composer.  You may download the zip but you will need to satisfy various dependency of which composer does automatically.

###Install Composer
If you are not already using [composer](http://getcomposer.org/) to manage your project dependencies, install composer.

    curl -s http://getcomposer.org/installer | php

###Configure composer.json
If you don't already have a composer.json file in your projects root, create one with the following contents: 

    {
        "require": {
            "interqualitas/php-client": "dev-master"
        }
    } 
        
If you do have a composer.json, add the following code to your composer.json required section:

    "interqualitas/php-client": "dev-master"
    
After setting up your json file simply install the package using

    php composer.phar install
    
###Usage
A simple usage example is as follows:

    <?php
        require_once 'vendor/autoload.php';
        $iq = new Interqualitas('username', 'api_key');
        $policyHolderApi = new Interqualitas\PolicyHolder($iq);
        
For more advanced instructions look at the full example in docs/example/full.php
