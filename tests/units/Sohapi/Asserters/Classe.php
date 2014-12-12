<?php

namespace Sohtest\Asserters;

class Classe extends Generic {

    protected $_idData = 'classe';
    protected $_idDatabis = 'class';
    public function get($ns) {
        $this->checkNS($ns);
        $data       = $this->_data[$this->_idData];
        $class      = array();

        if(!isset($data[$ns]))
            return $this->call('array', array());


        foreach ($data[$ns] as $d) {
            $class[] = $d[$this->_idDatabis];
        }

        return $this->call('array' , $class);
    }

    public function getExtends($ns, $classname)
    {
        $this->checkNS($ns);
        $data       = $this->_data[$this->_idData];

        if(!isset($data[$ns]))
            return $this->call('string', '');

        foreach ($data[$ns] as $d) {
            if($d[$this->_idDatabis] === $classname)
                return $this->call('string', $d['extends']);
        }

        return $this->call('string', '');
    }

    public function getImplements($ns, $classname)
    {
        $this->checkNS($ns);
        $data       = $this->_data[$this->_idData];

        if(!isset($data[$ns]))
            return $this->call('string', '');

        foreach ($data[$ns] as $d) {
            if($d[$this->_idDatabis] === $classname){
                return $this->call('string', $d['implements']);
            }
        }

        return $this->call('string', '');
    }
}
