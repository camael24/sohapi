<?php
namespace {
    use Hoa\Console\Chrome\Text;
    use Sohapi\Export;
    use Sohapi\Formatter\Cli;
    use Sohapi\Formatter\Html;
    use Sohapi\Proxy\Classes;

    require 'vendor/autoload.php';
    require 'Test/C.php';

    $html   = new Html(__DIR__ . '/html', __DIR__ . '/Output/Bootstrap/', array(
        'branch'    => 'master',
        'commit'    => 'ironfist',
        'commitUrl' => 'http://github.com/camael24/sohapi/'
    ));
    $api    = new Export();
    $export = $api
        ->classname('\\Sohapi\\Export')
        ->classname('\\Test\\C')
        ->classname('\\Hoa\\Console\\Chrome\\Text')
        ->classname('\\Foo')
        ->all()
        ->internal('fr')
        ->resolution('#^/#', '(?<classname>[^\\.]).html')
        ->proxy(new Classes())
        ->export($html);


    echo Text::columnize($export);
}