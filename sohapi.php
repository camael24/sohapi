<?php
require 'vendor/autoload.php';

$api = new \Sohapi\Export();
$api->classname('\\Sohapi\\Export')
    ->classname('\\Sohapi\\Classes')
    ->check('#^/Sohapi#')
    ->check(function ($classname) {

        if (preg_match('#^/Reflector#', $classname) > 0)
            return false;

        return true;
    })
    ->mandataire(new \Sohapi\Classes())
    ->export(new \Sohapi\Cli());


