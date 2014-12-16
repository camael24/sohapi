<?php
namespace Sohtest\Asserters;

use mageekguy\atoum;

class Test extends \atoum\test
{

    public function beforeTestMethod($testMethod)
    {
        //$this->define->model = '\Sohtest\Asserters\Model';
        //NEW API
        $this->define->namespace = '\Sohtest\Asserters\Ns';
        $this->define->class     = '\Sohtest\Asserters\Classe';
        $this->define->alias     = '\Sohtest\Asserters\Alias';
        $this->define->iface     = '\Sohtest\Asserters\Iface';
        $this->define->abs       = '\Sohtest\Asserters\Abs';
        $this->define->property  = '\Sohtest\Asserters\Property';
        $this->define->method    = '\Sohtest\Asserters\Method';
    }
}
