<?php
require 'vendor/autoload.php';

$api = new \Sohapi\Export();
$api->classname('/Sohapi/Export/')
    ->check('#^/Sohapi#')
    ->check(function ($classname) {

        return false;
    })
    ->export();


