<?php
namespace {
    use Hoa\File\Finder;
    use Sohapi\Export;
    use Sohapi\Formatter\Html;
    use Sohapi\Proxy\Classes;

    /**
     * Step 1. make an composer install
     * Step 2  git clone http://github.com/hoaproject/Central
     */

    require 'Central/Hoa/Core/Core.php';
    require 'vendor/autoload.php';

    $directory = __DIR__ . '/Central';

    $cmd = function ($command) use ($directory) {
        $re      = '';
        $process = proc_open($command, array(array("pipe", "r"), array("pipe", "w"), array('pipe', 'a')), $pipes, $directory);
        if (is_resource($process)) {
            $re = stream_get_contents($pipes[1]);
            fclose($pipes[1]);
            proc_close($process);

        }

        return $re;
    };

    $commit     = $cmd('git log -n 1 --pretty=format:%h');
    $commitLong = $cmd('git log -n 1 --pretty=format:%H');
    $branch     = $cmd('git rev-parse --abbrev-ref HEAD');

    $finder = new Finder();
    $finder->in('Central/Hoa')
        ->name("#\\.php$#")
        ->notIn('#Bin#')
        ->notIn('#Json#')
        ->notIn('#Filter#')
        ->maxDepth(5)
        ->size('>0');


    $html = new Html(__DIR__ . '/html', __DIR__ . '/Output/Bootstrap/', array(
        'branch'      => $branch,
        'commitshort' => $commit,
        'commitlong'  => $commitLong,
        'mainUrl'     => 'https://github.com/hoaproject/Central/tree/(?<commitlong>)/(?<file>)',
        'alt'         => array(
            'Gitweb' => 'http://git.hoa-project.net/Central.git/tree/(?<file>)\?id=(?<commitlong>)',
            'Pickacode' => 'https://pikacode.com/hoaproject/Central/files/(?<commitlong>)/(?<file>)'
        )
    ));
    $api  = new Export();

    foreach ($finder as $file)
        if ($file instanceof \SplFileInfo) {
            $dir  = $file->getPath();
            $ns   = str_replace('Central/', '', $dir);
            $name = substr($file->getBasename(), 0, -4);

            if (strlen($name) > 1)
                $api->classname($ns . '\\' . $name);
        }


    $api->all()
        ->internal('fr')
        ->resolution('#^/#', '(?<classname>[^\\.]).html')
        ->proxy(new Classes())
        ->export($html);

    echo 'Good job';
}