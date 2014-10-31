<?php
$finder = new \Hoa\File\Finder();
$finder
    ->in(__DIR__.'/Central/Hoa/Core')
    ->files()
    ->notIn('#^\.(git|hg)$#')
    ->notIn('#^(Tests|Command|Bin|Compiler|Console )$#')
    ->name('#\.php$#');

return $finder;