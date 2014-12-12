<?php

namespace tests\units\Sohapi\Parser\Php;

use mageekguy\atoum;

class Alias extends \atoum\test
{

    public function beforeTestMethod($testMethod)
    {
        $this->define->model = '\Sohtest\Asserters\Model';
    }


    public function testOneUse()
    {
        $source = '<?php
        namespace;
        use atoum\atoum;
        ';

        $this
            ->integer((new \Sohapi\Parser\Reader($source))->build())
            ->isIdenticalTo(0);

        $this
            ->model(\Sohapi\Parser\Model::getInstance()->getAll())
            ->useExist('' , ['atoum\atoum'])
            ->useNotExist('' , ['Foo\Bar\Qux' , 'Foox'])
        ;
    }

    public function testChainUse()
    {
        $source = '<?php
        namespace;
        use atoum\atoum, hoa\file, foo;
        ';

        $this
            ->integer((new \Sohapi\Parser\Reader($source))->build())
            ->isIdenticalTo(0);

        $this
            ->model(\Sohapi\Parser\Model::getInstance()->getAll())
            ->useExist('' , ['atoum\atoum', 'hoa\file', 'foo'])
            ->useNotExist('' , ['Foo\Bar\Qux' , 'Foox'])
        ;
    }

    public function testMultiUse()
    {
        $source = '<?php
        namespace;
        use atoum\atoum;
        use hoa\file;
        use foo;
        ';

        $this
            ->integer((new \Sohapi\Parser\Reader($source))->build())
            ->isIdenticalTo(0);

        $this
            ->model(\Sohapi\Parser\Model::getInstance()->getAll())
            ->useExist('' , ['atoum\atoum', 'hoa\file', 'foo'])
            ->useNotExist('' , ['Foo\Bar\Qux' , 'Foox'])
        ;
    }

    public function testMultiChainUse()
    {
        $source = '<?php
        namespace;
        use atoum\atoum, babar, Foox;
        use hoa\file;
        use foo;
        ';

        $this
            ->integer((new \Sohapi\Parser\Reader($source))->build())
            ->isIdenticalTo(0);

        $this
            ->model(\Sohapi\Parser\Model::getInstance()->getAll())
            ->useExist('' , ['atoum\atoum', 'hoa\file', 'foo', 'babar' , 'Foox'])
            ->useNotExist('' , ['Foo\Bar\Qux'])
        ;
    }
}
