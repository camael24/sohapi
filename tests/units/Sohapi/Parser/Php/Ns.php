<?php

namespace tests\units\Sohapi\Parser\Php;

use mageekguy\atoum;

class Ns extends \Sohtest\Asserters\Test
{
    public function testRoot()
    {
        $source = '<?php namespace; {}';

        $this
            ->integer((new \Sohapi\Parser\Reader($source))->build())
            ->isIdenticalTo(0);

        $this
            ->model(\Sohapi\Parser\Model::getInstance()->getAll())
            ->nsExist('')
            ->nsNotExist('Foo');
    }

    public function testOneLevel()
    {
        $source = '<?php namespace Foo; {}';

        $this
            ->integer((new \Sohapi\Parser\Reader($source))->build())
            ->isIdenticalTo(0);

        $this
            ->model(\Sohapi\Parser\Model::getInstance()->getAll())
            ->nsExist('Foo')
            ->nsNotExist('');
    }

    public function testDeepLevel()
    {
        $source = '<?php namespace Foo\Bar\Qux; {}';

        $this
            ->integer((new \Sohapi\Parser\Reader($source))->build())
            ->isIdenticalTo(0);

        $this
            ->model(\Sohapi\Parser\Model::getInstance()->getAll())
            ->nsExist('Foo\Bar\Qux')
            ->nsNotExist('');
    }

    public function testMultiLevel()
    {
           $source = '<?php
           namespace Foo\Bar\Qux; {}
           namespace Foox{ {} }
           ?>';

        $this
            ->integer((new \Sohapi\Parser\Reader($source))->build())
            ->isIdenticalTo(0);

        $this
            ->model(\Sohapi\Parser\Model::getInstance()->getAll())
            ->nsExist(['Foo\Bar\Qux' , 'Foox'])
            ->nsNotExist('');
    }
}
