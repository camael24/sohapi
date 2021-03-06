<?php

namespace tests\units\Sohapi\Parser\Php;

use mageekguy\atoum;

class Variable extends \Sohtest\Asserters\Test
{
    public function testClassicClass()
    {
        $source = '<?php
        class Foo {
            public static $hello = array();
            protected $_bar = \'waza\';
            private $aaa = null;
            private $a;
        };';

        $this
            ->integer((new \Sohapi\Parser\Reader($source))->build())
            ->isIdenticalTo(0);

        $this->class->get('')->contains('Foo');

        $this->property->get('', 'Foo')
            ->containsValues(['$hello', '$_bar', '$aaa', '$a']);

        $this->property->values('', 'Foo', '$hello')
            ->isIdenticalTo('array()');

        $this->property->values('', 'Foo', '$a')
            ->isIdenticalTo('');

        $this->property->visibility('', 'Foo', '$hello')
            ->isIdenticalTo('public');

        $this->property->visibility('', 'Foo', '$_bar')
            ->isIdenticalTo('protected');

        $this->property->visibility('', 'Foo', '$aaa')
            ->isIdenticalTo('private');

        $this->property->isStatic('', 'Foo', '$a')
            ->isFalse();

        $this->property->isStatic('', 'Foo', '$aaa')
            ->isFalse();

        $this->property->isStatic('', 'Foo', '$hello')
            ->isTrue();
    }

    public function testOlderClass()
    {
        $source = '<?php
        class Foo {
            static $hello = array();
            private $aaa = null;
            private $a;
        };';

        $this
            ->integer((new \Sohapi\Parser\Reader($source))->build())
            ->isIdenticalTo(0);

        $this->class->get('')->contains('Foo');

        $this->property->get('', 'Foo')
            ->containsValues(['$hello', '$aaa', '$a']);

        $this->property->values('', 'Foo', '$hello')
            ->isIdenticalTo('array()');

        $this->property->values('', 'Foo', '$a')
            ->isIdenticalTo('');

        $this->property->visibility('', 'Foo', '$hello')
            ->isIdenticalTo('public');

        $this->property->visibility('', 'Foo', '$aaa')
            ->isIdenticalTo('private');

        $this->property->isStatic('', 'Foo', '$a')
            ->isFalse();

        $this->property->isStatic('', 'Foo', '$aaa')
            ->isFalse();

        $this->property->isStatic('', 'Foo', '$hello')
            ->isTrue();
    }
}
