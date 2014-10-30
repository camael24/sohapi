<?php
$finder = new \Hoa\File\Finder();
$finder
    ->in(__DIR__.'/data')
    ->maxDepth(1)
    ->files()
    ->notIn('#^\.(git|hg|Tests|Command)$#')
    ->notIn('#^(Tests|Command)$#')
    ->name('#\.php$#');

return $finder;