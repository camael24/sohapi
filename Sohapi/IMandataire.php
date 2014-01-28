<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 27/01/14
 * Time: 10:54
 */

namespace Sohapi;


interface IMandataire {
    public function setClassname(Array $classname);

    public function setCheck(Array $check);

    public function setResolve(Array $resolve);

    public function process();

    public function getIgnoreClasses();

    public function getClasses();

    public function getValidClasses();

}