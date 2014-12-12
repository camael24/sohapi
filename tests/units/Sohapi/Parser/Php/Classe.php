<?php

namespace tests\units\Sohapi\Parser\Php;

use mageekguy\atoum;

class Classe extends \Sohtest\Asserters\Test
{
    public function testClassicClass()
    {
        $source = '<?php
        namespace;
        class Foo {};';

        $this
            ->integer((new \Sohapi\Parser\Reader($source))->build())
            ->isIdenticalTo(0);

        $this->class->get('')
            ->contains('Foo')
        ;

    }

    public function testNsClass()
    {
        $source = '<?php
        namespace Foo\Bar;
        class Foo {};
        class Bar {}
        ';

        $this
            ->integer((new \Sohapi\Parser\Reader($source))->build())
            ->isIdenticalTo(0);

        $this->class->get('Foo\Bar')
            ->containsValues(['Foo', 'Bar'])
            ->notContainsValues(['Foox'])
        ;
    }

    public function testMultiNsClass()
    {
        $source = '<?php
        namespace Foo\Bar;
        class Foo {}
        class Bar {}
        namespace Babar {
            class Hello {}
        }
        ';

        $this
            ->integer((new \Sohapi\Parser\Reader($source))->build())
            ->isIdenticalTo(0);

        $this->class->get('Foo\Bar')
            ->containsValues(['Foo', 'Bar'])
            ->notContainsValues(['Foox']);

        $this->class->get('Babar')
            ->contains('Hello')
        ;


    }

    public function testExtendsClass()
    {
        $source = '<?php namespace Foo\Bar;
        class Foo {}
        class Bar extends Foo {}';

        $this
            ->integer((new \Sohapi\Parser\Reader($source))->build())
            ->isIdenticalTo(0);

         $this->class->get('Foo\Bar')
            ->containsValues(['Foo', 'Bar'])
            ->notContainsValues(['Foox']);

        $this->class->getExtends('Foo\Bar' , 'Foo')
            ->isIdenticalTo('');
        $this->class->getExtends('Foo\Bar' , 'Bar')
            ->isIdenticalTo('Foo');

    }

    public function testImplementsClass()
    {
        $source = '<?php namespace Foo\Bar;
        interface Foo {}
        class Bar implements Foo {}
        class So implements Foo,\SPLFileQueue {}';

        $this
            ->integer((new \Sohapi\Parser\Reader($source))->build())
            ->isIdenticalTo(0);

         $this->class->get('Foo\Bar')
            ->containsValues(['Bar', 'So'])
            ->notContainsValues(['Foox']);

        $this->class->getImplements('Foo\Bar' , 'Bar')
            ->isIdenticalTo('Foo');

        $this->class->getImplements('Foo\Bar' , 'So')
            ->isIdenticalTo('Foo,\SPLFileQueue');

        $this->iface->get('Foo\Bar')
            ->contains('Foo');
    }

    public function testExtendsImplementsClass()
    {
        $source = '<?php namespace Foo\Bar;
        interface Babar {}
        interface Foo extends Babar {}
        class Hello {}
        class Bar extends Hello implements Foo {}
        ';

        $this
            ->integer((new \Sohapi\Parser\Reader($source))->build())
            ->isIdenticalTo(0);

         $this->class->get('Foo\Bar')
            ->containsValues(['Bar', 'Hello'])
            ->notContainsValues(['Foox']);

        $this->class->getExtends('Foo\Bar' , 'Bar')
            ->isIdenticalTo('Hello');

        $this->class->getImplements('Foo\Bar' , 'Bar')
            ->isIdenticalTo('Foo');

        $this->iface->get('Foo\Bar')
            ->containsValues(['Foo', 'Babar']);

        $source = '<?php namespace Foo\Bar;
        interface Babar {}
        interface Foo extends Babar {}
        class Hello {}
        class Bar implements Foo extends Hello{}
        ';

        $this
            ->integer((new \Sohapi\Parser\Reader($source))->build())
            ->isIdenticalTo(0);

         $this->class->get('Foo\Bar')
            ->containsValues(['Bar', 'Hello'])
            ->notContainsValues(['Foox']);

        $this->class->getExtends('Foo\Bar' , 'Bar')
            ->isIdenticalTo('Hello');

        $this->class->getImplements('Foo\Bar' , 'Bar')
            ->isIdenticalTo('Foo');

        $this->iface->get('Foo\Bar')
            ->containsValues(['Foo', 'Babar']);
    }

    public function testAbstractClass()
    {
         $source = '<?php namespace Foo\Bar;
        abstract class Hello {}';

        $this
            ->integer((new \Sohapi\Parser\Reader($source))->build())
            ->isIdenticalTo(0);

         $this->abs->get('Foo\Bar')
            ->containsValues(['Hello'])
            ->notContainsValues(['Foox']);
    }

    public function testAbstractExtendsClass()
    {
         $source = '<?php namespace Foo\Bar;
        abstract class Hello extends Foo{}';

        $this
            ->integer((new \Sohapi\Parser\Reader($source))->build())
            ->isIdenticalTo(0);

         $this->abs->get('Foo\Bar')
            ->containsValues(['Hello'])
            ->notContainsValues(['Foox']);

        $this->abs->getExtends('Foo\Bar', 'Hello')
            ->isIdenticalTo('Foo');

    }

//    public function testAbstractExtendsImplementsClass()
//    {
//         $source = '<?php namespace Foo\Bar;
//        abstract class Hello extends Foo implements \SPLFileQueue{}';
//
//        $this
//            ->integer((new \Sohapi\Parser\Reader($source))->build())
//            ->isIdenticalTo(0);
//
//         $this->abs->get('Foo\Bar')
//            ->containsValues(['Hello'])
//            ->notContainsValues(['Foox']);
//
//        $this->abs->getExtends('Foo\Bar', 'Hello')
//            ->isIdenticalTo('Foo');
//
//        $this->abs->getImplements('Foo\Bar', 'Hello')
//            ->isIdenticalTo('\SPLFileQueue');
//
//        $source = '<?php namespace Foo\Bar;
//        abstract class Hello implements \SPLFileQueue extends Foo {}';
//
//        $this
//            ->integer((new \Sohapi\Parser\Reader($source))->build())
//            ->isIdenticalTo(0);
//
//         $this->abs->get('Foo\Bar')
//            ->containsValues(['Hello'])
//            ->notContainsValues(['Foox']);
//
//        $this->abs->getExtends('Foo\Bar', 'Hello')
//            ->isIdenticalTo('Foo');
//
//        $this->abs->getImplements('Foo\Bar', 'Hello')
//            ->isIdenticalTo('\SPLFileQueue');
//
//    }
}
