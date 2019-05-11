<?php 
    require 'paypal/autoload.php';

    define('URL_SITIO','http://localhost:8081/paypal');

    $apiContext= new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential(
           'AZsKT3ImvDWfYvoCCejt_ijL_TW7JXddD60GKKtxMhs1TxFuYrQ4eGyFkP7uziD7XZ3-A5odsJ1vDIdM', //clienteID
           'ENvJQvmaNQsL5OEyUdgKdo16OscYeZhhGBE-eEpmCZfd5v8o44hrxmGq4MSOBZwtnXvowC-KDfatK7yS' //Secret
        )
    );
