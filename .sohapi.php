<?php
$finder = new \Hoa\File\Finder();
$finder
    ->in(__DIR__.'/data/Central')
    ->files()
    ->notIn('#^\.(git|hg)$#')
    ->notIn('#^(Bin|Filter)$#')
    ->name('#\.php$#');

return ['C:\www\sohapi\data\Central\Hoa\Console\Processus.php', 'C:\www\sohapi\data\Central\Hoa\Stream\Stream.php'];