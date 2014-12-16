<?php

namespace Sohtest\Asserters;

class Method extends Generic {

    public function get($ns, $class) {
        $this->checkNS($ns);
        $data       = $this->_data['methods'];
        $methods    = array();

        if(!isset($data[$ns]))
            return $this->call('array', array());

        if(!isset($data[$ns][$class]))
            return $this->call('array', array());


        foreach ($data[$ns][$class] as $d) {
            $methods[] = $d['name'];
        }

        return $this->call('array' , $methods);
    }

    public function args($ns, $class, $method) {
        $this->checkNS($ns);
        $data       = $this->_data['methods'];
        $args       = array();

        if(!isset($data[$ns]))
            return $this->call('array', array());

        if(!isset($data[$ns][$class]))
            return $this->call('array', array());


        foreach ($data[$ns][$class] as $d) {
            //var_dump($d);
           $args[$d['name']] = $d;
        }

        return $this->call('array' , $args);
    }

}
