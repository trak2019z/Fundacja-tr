<?php
/**
 * Created by PhpStorm.
 * User: Marta
 * Date: 14.11.2018
 * Time: 22:34
 */
$configuration -> loadFromExtension('doctrine', array(
    'dbal' => array(
        'driver' => '%database_driver%',
        'host' => '%database_host%',
        'dbname' => '%database_name%',
        'user' => '%database_user%',
        'password' => '%database_password',
    ),
));
