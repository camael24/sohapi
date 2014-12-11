<?php
use mageekguy\atoum;
define('CODE_COVERAGE_ROOT', __DIR__ . DIRECTORY_SEPARATOR . 'tests/coverage');

$runner->addTestsFromDirectory(__DIR__ . '/tests');
$runner->setBootstrapFile(__DIR__ . '/.bootstrap.php');
