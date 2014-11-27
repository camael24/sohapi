<?php
$finder = new \Hoa\File\Finder();
$finder
    ->in(__DIR__.'/Central/Hoa/')
    ->files()
    ->notIn('#^\.(git|hg)$#')
    ->notIn('#^(Tests|Command|Bin)$#')
    ->name('#\.php$#');

//return $finder;
return [];