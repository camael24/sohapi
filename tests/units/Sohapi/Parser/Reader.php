<?php

namespace tests\units\Sohapi\Parser;

use mageekguy\atoum;

class Reader extends atoum\test
{
    public function testClass()
    {
        $source = '<?php class Hello {}';
        $object = $this->newTestedInstance($source);

        $this
            ->object($object)->isInstanceOf('Sohapi\Parser\Reader')
            ->array($object->getTokens())->hasSize(7)
            ->string[0][1]->isIdenticalTo('<?php ')
            ->string[3][1]->isIdenticalTo('Hello')
        ;

        $this->integer($object->build())->isIdenticalTo(0);
    }

    public function testFunction()
    {
        $source = '<?php function foo(){ echo "bar" }';
        $object = $this->newTestedInstance($source);

        $this
            ->object($object)->isInstanceOf('Sohapi\Parser\Reader')
            ->array($object->getTokens())->hasSize(13)
            ->string[0][1]->isIdenticalTo('<?php ')
            ->string[3][1]->isIdenticalTo('foo')
        ;

        $this->integer($object->build())->isIdenticalTo(0);

    }

    public function testBuild()
    {
        $source = '<?php class Hello extends Foo {}';
        $count  = $this->newTestedInstance($source)->build();

        $this->integer($count)->isIdenticalTo(0);
    }
}
