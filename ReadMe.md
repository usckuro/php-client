This wrapper provides access to the Interqualitas API.  It provides all functionality to the API through PHP.

If you know how to handle installation and usage of composer modules you can skip the rest of this.  A quick example of the functions can be found under
    docs/example/full

Full documentation of the API is at dev.interqualitas.net

#Instalation
To install Interqualitas into your project we recommend composer.  You may download the zip but you will need to satisfy various dependency of which composer does automatically.

##Composer
Add the following code to your composer.json required section:

    "interqualitas/php-wrapper": "master"
    
or run this command from your projects root

    composer require
    
##Usage
A simple usage example is as follows:

    <?php
        require_once 'vendor/autoload.php';
        $iq = new Interqualitas('username', 'password);
        $policyHolderApi = new Interqualitas\PolicyHolder($iq);
        
For more advanced instructions look at the full example in docs/example/full.php