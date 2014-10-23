<?php

class MyClass
{
    const CONSTANT = 'constant value';

    public function showConstant()
    {
    }
}

class Foo
{
    public $a = array(0 => MyClass::CONSTANT);

}
