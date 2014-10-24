<?php
require __DIR__.'/../../vendor/autoload.php';

$file = realpath(__DIR__.'/../../data/const.php');

(new \Sohapi\Parser\Reader($file, 'simple'))->build();

echo 'EOF';