<?php
require 'vendor/autoload.php';

$api = new \Sohapi\Export();
$api->classname('\\Sohapi\\Export')
    ->classname('\\Sohapi\\Classes')
    ->check('#^/Sohapi#')
    ->check(function ($classname) {

        return false;
    })
    ->mandataire(new \Sohapi\Classes())
    ->export(new \Sohapi\Cli());


