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

        $this->array($name)->contains($ns);


        return $this;
    }

    public function classExists($ns, $class)
    {
        $this->nsExist($ns);
        $element = $this->value['classe'];
        $bool    = false;

        foreach ($element as $key => $v) {
            foreach ($v as $key => $value) {
                if($value['class'] === $class){
                    $bool = true;
                }
            }
        }

        if($bool === false) {
            $this->fail(sprintf($this->getLocale()->_('%s not exists'), $class));
        }
        else {
            $this->pass();
        }

        return $this;
    }



}
