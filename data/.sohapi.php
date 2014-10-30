<?php
$finder = new \Hoa\File\Finder();
$finder
    ->in(__DIR__.'/framework')
    ->files()
    ->notIn('#^\.(git|hg|Tests|Command)$#')
    ->notIn('#^(Tests|Command|Bin)$#')
    ->name('#\.php$#');

return $finder;