<?php
require __DIR__.'/../../vendor/autoload.php';

$file = realpath(__DIR__.'/../../data/simple.php');

$parser = new \Sohapi\Parser\Reader($file);

$parser->build();
echo 'EOF';
