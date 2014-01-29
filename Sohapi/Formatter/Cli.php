<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 27/01/14
 * Time: 15:38
 */

namespace Sohapi\Formatter;


use Sohapi\Proxy\IProxy;

class Cli implements IExport
{
    public function process(IProxy $mandataire)
    {
        $classes = $mandataire->getValidClasses();

        foreach ($classes as $classname => $data) {
            echo 'EXE ' . $classname . "\n";

            echo "\t" . 'Properties: ' . count($data['properties']) . "\n";
            echo "\t" . 'Methods: ' . count($data['methods']) . "\n";

        }

        $ignore = $mandataire->getIgnoreClasses();

        foreach ($ignore as $classname => $data)
            echo 'IGN ' . $classname . "\t" . $data['status'] . "\n";

    }

    public function setResolve(Array $resolve)
    {

    }


} 