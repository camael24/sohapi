<?php
require __DIR__.'/../../vendor/autoload.php';

$file = realpath(__DIR__.'/../../data/complete.php');

$parser = new \Sohapi\Parser\Reader($file);

$parser->build();
echo 'EOF';
