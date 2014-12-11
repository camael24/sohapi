<?php

namespace tests\units\Sohapi\Parser;

use mageekguy\atoum;

class Model extends \atoum\test
{

    public function beforeTestMethod($testMethod)
    {
        $this->define->model = '\Sohtest\Asserters\Model';
    }


    public function testClass()
    {
        $source = '<?php class Hello {}';
        $object = new \Sohapi\Parser\Reader($source);

        $this
            ->object($object)->isInstanceOf('Sohapi\Parser\Reader')
            ->array($object->getTokens())->hasSize(7)
            ->string[0][1]->isIdenticalTo('<?php ')
            ->string[3][1]->isIdenticalTo('Hello')
        ;

        $this->integer($object->build())->isIdenticalTo(0);

        $storage = \Sohapi\Parser\Model::getInstance();


        $this
            ->model($storage->getAll())
            ->classExists('', 'Hello');
    }
}
