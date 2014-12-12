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

        $this->namespace->get()
            ->contains('')
            ->notContains('Foo');
    }

    public function testOneLevel()
    {
        $source = '<?php namespace Foo; {}';

        $this
            ->integer((new \Sohapi\Parser\Reader($source))->build())
            ->isIdenticalTo(0);

       $this->namespace->get()
            ->contains('Foo')
            ->notContains('');
    }

    public function testDeepLevel()
    {
        $source = '<?php namespace Foo\Bar\Qux; {}';

        $this
            ->integer((new \Sohapi\Parser\Reader($source))->build())
            ->isIdenticalTo(0);

        $this->namespace->get()
            ->contains('Foo\Bar\Qux')
            ->notContains('');
    }

    public function testMultiLevel()
    {
           $source = '<?php
           namespace Foo\Bar\Qux; {}
           namespace Foox{ {} }
           ';

        $this
            ->integer((new \Sohapi\Parser\Reader($source))->build())
            ->isIdenticalTo(0);

        $this->namespace->get()
            ->containsValues(['Foo\Bar\Qux' , 'Foox'])
            ->notContains('');
    }
}
