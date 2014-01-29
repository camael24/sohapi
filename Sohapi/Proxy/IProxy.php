<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 27/01/14
 * Time: 10:54
 */

namespace Sohapi\Proxy;


interface IProxy
{
    public function setClassname(Array $classname);

    public function setCheck($check);

    public function process();

    public function getIgnoreClasses();

    public function getClasses();

    public function getAllClasses();

    public function getValidClasses();

    public function getAllClassname();

    public function getAllValidClassname();

    public function getAllValidClassnameWithoutInternal();
}