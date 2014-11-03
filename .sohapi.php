<?php
$finder = new \Hoa\File\Finder();
$finder
    ->in(__DIR__.'/data/Central')
    ->files()
    ->notIn('#^\.(git|hg)$#')
    ->notIn('#^(Bin|Filter)$#')
    ->name('#\.php$#');

return $finder;