<?php

namespace Sohtest\Asserters;

class Alias extends Generic {

    protected $_alias = null;
    public function get($ns) {

        $this->checkNS($ns);
        $this->_ns  = $ns;
        $data       = $this->_data['use'];
        $class      = array();

        if(!isset($data[$ns]))
            return $this->call('array', array());


        foreach ($data[$ns] as $d) {
            $class[] = $d['class'];
        }

        return $this->call('array' , $class);
    }

    public function hasAlias($ns, $fqcn)
    {
        $this->checkNS($ns);
        $this->_ns  = $ns;
        $data       = $this->_data['use'];
        $class      = array();
        $return     = false;

        if(isset($data[$ns])){
            foreach ($data[$ns] as $d) {
                if($d['class'] === $fqcn){
                    if(!empty($d['as'])){
                        $return = true;
                    }
                }
            }
        }

        return $this->call('boolean', $return);
    }

    public function getAlias($ns, $fqcn)
    {

        $this->checkNS($ns);
        $this->_ns  = $ns;
        $data       = $this->_data['use'];
        $class      = array();
        $return     = false;

        if(isset($data[$ns])){
            foreach ($data[$ns] as $d) {
                if($d['class'] === $fqcn){
                    if(!empty($d['as'])){
                        return $this->call('string', $d['as']);
                    }
                }
            }
        }


        return $this->call('string', '');
    }
}
