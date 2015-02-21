<?php

require_once __DIR__ . '/../vendor/autoload.php';

new \cotcot\core\RuntimeContext(array(
    'initializers' => array(
        array(
            'classname' => '\\cotcot\\component\\initializer\\CharsetAndTimezoneInitializer',
            'singleton' => true,
            'attributes' => array(
                'charset' => 'UTF-8',
                'timezone' => 'Europe/Paris'
            )
        )
    ))
);
