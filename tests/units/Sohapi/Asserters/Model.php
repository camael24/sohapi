<?php

namespace Sohtest\Asserters;

class Model extends \atoum\asserters\phpArray {

    public function setWith($value, $checkType = true)
     {
        parent::setWith($value);
        if ($checkType === true) {
            if (is_array($this->value) === false) {
                $this->fail(sprintf($this->getLocale()->_('%s is not an array'), $this));
            }
            else {
                $this->pass();
            }
        }
        return $this;
    }

    public function nsExist($ns)
    {

        $element = $this->value;
        $name    = $element['namespace'];

        if(!is_array($ns))
            $ns = array($ns);

        foreach ($ns as $n) {
            $this->array($name)->contains($n);
        }

        return $this;
    }

    public function nsNotExist($ns)
    {
        $element = $this->value;
        $name    = $element['namespace'];

        if(!is_array($ns))
            $ns = array($ns);

        foreach ($ns as $n) {
            $this->array($name)->notContains($n);
        }

        return $this;
    }

    public function classExist($ns, $class)
    {
        if(is_string($class))
            $class = array($class);

        $this->nsExist($ns);
        $element = $this->value['classe'];
        $element = (isset($element[$ns])) ? $element[$ns] : array();
        $bool    = $this->exist($element, $class, true);

        if($bool === false) {
            $this->fail(sprintf($this->getLocale()->_('%s is not in class declaration'), implode(',' , $class)));
        }
        else {
            $this->pass();
        }


        return $this;
    }

    public function classNotExist($ns, $class)
    {
        if(is_string($class))
            $class = array($class);

        $this->nsExist($ns);
        $element = $this->value['classe'];
        $element = (isset($element[$ns])) ? $element[$ns] : array();
        $bool    = $this->exist($element, $class, false);

        if($bool === true) {
            $this->fail(sprintf($this->getLocale()->_('%s is in class declaration'), implode(',' , $class)));
        }
        else {
            $this->pass();
        }


        return $this;
    }

    protected function exist($element, $use, $default)
    {
        if(empty($element))
            return false;

        $oracle = $default;
        $in     = function($needle, $haystack) {

            foreach ($needle as $value) {
                if($value['class'] === $haystack)
                    return true;
            }

            return false;
        };

        foreach ($use as $e) {
            if($oracle === $default) {
                $oracle = $in($element, $e);
            }
        }

        return $oracle;
    }

    public function useExist($ns, $use)
    {
        if(is_string($use))
            $use = array($use);

        $this->nsExist($ns);
        $element = $this->value['use'];
        $element = (isset($element[$ns])) ? $element[$ns] : array();
        $bool    = $this->exist($element, $use, true);

        if($bool === false) {
            $this->fail(sprintf($this->getLocale()->_('%s is not in use statement'), implode(',' , $use)));
        }
        else {
            $this->pass();
        }


        return $this;
    }

    public function useNotExist($ns, $use)
    {
        if(is_string($use))
            $use = array($use);

        $this->nsExist($ns);
        $element = $this->value['use'];
        $element = (isset($element[$ns])) ? $element[$ns] : array();
        $bool    = $this->exist($element, $use, false);

        if($bool === true) {
            $this->fail(sprintf($this->getLocale()->_('%s is in use statement'), implode(',' , $use)));
        }
        else {
            $this->pass();
        }


        return $this;
    }

    public function classExtends() {

    }



}
