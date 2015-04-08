<?php
return array(
    'zf-oauth2' => array(
        'db' => array(
            'dsn'      => 'mysql:dbname=ad_5b8045e5aae6ad9;host=us-cdbr-iron-east-01.cleardb.net', // for example "mysql:dbname=oauth2_db;host=localhost"
            'username' => 'bf1c9d658ff1ed',
            'password' => '3175e2fb',
        ),
        'allow_implicit' => true, // default (set to true when you need to support browser-based or mobile apps)
        'access_lifetime' => 3600, // default (set a value in seconds for access tokens lifetime)
        'enforce_state'  => true,  // default
        'storage'        => 'ZF\OAuth2\Adapter\PdoAdapter', // service name for the OAuth2 storage adapter
    ),
);
