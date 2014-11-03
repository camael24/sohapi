<?php
$finder = new \Hoa\File\Finder();
$finder
    ->in(__DIR__.'/Central/Hoa/Console')
    ->files()
    ->notIn('#^\.(git|hg)$#')
    ->notIn('#^(Tests|Command|Bin|Compiler|Console )$#')
    ->name('#\.php$#');

return ['C:\www\sohapi\data\Central\Hoa\Console\Processus.php'];