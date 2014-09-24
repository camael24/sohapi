<?php
require_once 'vendor/autoload.php';

$parser = new \Sohapi\Parser\Reader(__DIR__.'/data/simple.php');

$parser->build();
echo 'EOF';
