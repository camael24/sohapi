<?php

namespace Sohtest\Asserters;

class Property extends Generic {

    // TODO : Refactor TEST

    public function get($ns, $class) {
        $this->checkNS($ns);
        $data       = $this->_data['properties'];
        $properties = array();

        if(!isset($data[$ns]))
            return $this->call('array', array());

        if(!isset($data[$ns][$class]))
            return $this->call('array', array());


        foreach ($data[$ns][$class] as $d) {
            $properties[] = $d['name'];
        }

        return $this->call('array' , $properties);
    }

    public function values($ns, $class, $property)
    {
        $this->checkNS($ns);
        $data       = $this->_data['properties'];
        $properties = array();

        if(!isset($data[$ns]))
            return $this->call('array', array());

        if(!isset($data[$ns][$class]))
            return $this->call('array', array());


        foreach ($data[$ns][$class] as $d) {
            if($d['name'] === $property){
                return $this->call('string' , $d['default']);
            }
        }

        return $this->call('string' , '');
    }

    public function visibility($ns, $class, $property)
    {
        $this->checkNS($ns);
        $data       = $this->_data['properties'];
        $properties = array();

        if(!isset($data[$ns]))
            return $this->call('array', array());

        if(!isset($data[$ns][$class]))
            return $this->call('array', array());


        foreach ($data[$ns][$class] as $d) {
            if($d['name'] === $property){
                return $this->call('string' , $d['visibility']);
            }
        }

        return $this->call('string' , 'public');
    }

    public function isStatic($ns, $class, $property)
    {
        $this->checkNS($ns);
        $data       = $this->_data['properties'];
        $properties = array();

        if(!isset($data[$ns]))
            return $this->call('array', array());

        if(!isset($data[$ns][$class]))
            return $this->call('array', array());


        foreach ($data[$ns][$class] as $d) {
            if($d['name'] === $property){
                return $this->call('boolean' , $d['static']);
            }
        }

        return $this->call('boolean' , false);
    }
}
