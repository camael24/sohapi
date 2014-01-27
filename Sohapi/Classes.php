<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 27/01/14
 * Time: 15:37
 */

namespace Sohapi;


class Classes implements IMandataire
{

    private $_class = array();
    private $_check = array();
    private $_register = array();
    private $_resolve = array();

    public function setClassname(Array $classname)
    {
        $this->_class = array_merge($this->_class, $classname);
    }

    public function setCheck(Array $check)
    {
        $this->_check = array_merge($this->_check, $check);
    }

    public function setResolve(Array $resolve)
    {
        $this->_resolve = array_merge($this->_resolve, $resolve);
    }

    public function process()
    {
        foreach ($this->_class as $class)
            $this->exportClass($class);
    }

    protected function allow($classname)
    {

        foreach ($this->_check as $check)
            if ($check instanceof \Closure) {
                if ($bool = $check($classname) === true)
                    return true;


            } else {
                if (preg_match($check, $classname) === true)
                    return true;
            }


        return false;
    }


    protected function exportClass($classname)
    {
        if (!class_exists($classname, true))
            return array(
                'status' => 'not_exists',
                'class' => $classname
            );


        if ($this->allow($classname) === false)
            return array(
                'status' => 'ignored',
                'class' => $classname
            );


        return $this->export($classname);
    }

    private function export($class){


        return array(
            'status' => ''
        );
    }

    public function getIgnoreClasses()
    {
    }

} 