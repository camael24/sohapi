<?php
$finder = new \Hoa\File\Finder();
$finder
    ->in(__DIR__.'/data/Central/')
    ->files()
    ->notIn('#^\.(git|hg)$#')
    ->name('#\.php$#');

return $finder;