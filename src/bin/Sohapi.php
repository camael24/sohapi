<?php
require __DIR__.'/../../vendor/autoload.php';

$file = realpath(__DIR__.'/../../data/simple.php');

(new \Sohapi\Parser\Reader($file))->build();
(new \Sohapi\Formatter\Cli())->render();
echo 'EOF';