<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 27/01/14
 * Time: 10:54
 */

namespace Sohapi\Formatter;


use Sohapi\Proxy\IProxy;

interface IExport {
    public function process(IProxy $mandataire);

    public function setResolve(Array $resolve);
}