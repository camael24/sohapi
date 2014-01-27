<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 27/01/14
 * Time: 10:54
 */

namespace Sohapi;


interface IExport {
    public function process(IMandataire $mandataire);

}