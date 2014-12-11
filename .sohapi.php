<?php
$finder = new \Hoa\File\Finder();
$finder
    ->in(__DIR__.'/data/Central/Hoa/Eventsource')
    ->files()
//    ->maxDepth(1)
    ->notIn('#^\.(git|hg)$#')
    ->notIn('#(Tests|Command|Documentation|Bin|Test|Filter)#')
    ->name('#\.php$#');

return $finder;