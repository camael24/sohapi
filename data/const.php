<?php
$a  = '';
function foo() {
    echo 'bar';
}
class MyClass
{
    public function showConstant()
    {
    }
}

class Foo
{
    public $a = array(0 => MyClass::CONSTANT);

}
