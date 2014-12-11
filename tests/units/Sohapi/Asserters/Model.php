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
        $this->nsExist($ns);
        $element = $this->value['classe'];
        $bool    = false;


        if(!is_array($class))
            $class = array($class);

        foreach ($class  as $c) {
            foreach ($element as $key => $v) {
                foreach ($v as $key => $value) {
                    if($value['class'] !== $c){
                        $this->fail(sprintf($this->getLocale()->_('%s not exists in model'), $c));
                    }
                }
            }
        }

        return $this;
    }

    public function classNotExist($ns, $class)
    {
        $this->nsExist($ns);
        $element = $this->value['classe'];
        $bool    = false;


        if(!is_array($class))
            $class = array($class);

        foreach ($class  as $c) {
            foreach ($element as $key => $v) {
                foreach ($v as $key => $value) {
                    if($value['class'] === $c){
                        $this->fail(sprintf($this->getLocale()->_('%s exists in model'), $c));
                    }
                }
            }
        }

        return $this;
    }

    public function useExist($ns, $use) {

    }



}
