<?php
require 'vendor/autoload.php';
require 'Test/C.php';

$api = new \Sohapi\Export();
$api
    ->psr0('vendor/hoa', '\\Hoa', '#Bin#')
    ->psr0('vendor/sohoa', '\\Sohoa', '#Bin#')
//    ->classname('\\Sohoa\\Framework\\Framework')
    ->parse('^/Hoa')
    ->parse('^/Sohoa')
    ->notParse('Combinatorics')
    ->notParse('Xyl')
    ->notParse('Tests')
    ->setResolution('^/', "(?<classname>[^\\.]).html")
    ->export(__DIR__ . '/html');
