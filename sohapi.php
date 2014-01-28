<?php
require 'vendor/autoload.php';


$api = new \Sohapi\Export();
$api->classname('\\Sohapi\\Export')
    ->classname('\\Sohapi\\Classes')
    ->check('#^/Sohapi#')
    ->check(function ($classname) {
        return true;
    })
    ->mandataire(new \Sohapi\Classes())
    ->export(new \Sohapi\Cli());


