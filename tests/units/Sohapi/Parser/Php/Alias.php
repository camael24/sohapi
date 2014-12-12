<?php

namespace tests\units\Sohapi\Parser\Php;

use mageekguy\atoum;

class Alias extends \Sohtest\Asserters\Test
{
    public function testOneUse()
    {
        $source = '<?php
        namespace;
        use atoum\atoum;
        ';

        $this
            ->integer((new \Sohapi\Parser\Reader($source))->build())
            ->isIdenticalTo(0);

        $this->alias->get('')
            ->contains('atoum\atoum')
            ->notContainsValues(['Foo\Bar\Qux' , 'Foox'])
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

        $this->alias->get('')
            ->containsValues(['atoum\atoum', 'hoa\file', 'foo'])
            ->notContainsValues(['Foo\Bar\Qux' , 'Foox'])
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

        $this->alias->get('')
            ->containsValues(['atoum\atoum', 'hoa\file', 'foo'])
            ->notContainsValues(['Foo\Bar\Qux' , 'Foox'])
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

        $this->alias->get('')
            ->containsValues(['atoum\atoum', 'hoa\file', 'foo', 'babar' , 'Foox'])
            ->notContainsValues(['Foo\Bar\Qux'])
        ;
    }

    public function testNsMultiChainUse()
    {
        $source = '<?php
        namespace Foor\Bar;
        use atoum\atoum, babar, Foox;
        use hoa\file;
        use foo;
        ';

        $this
            ->integer((new \Sohapi\Parser\Reader($source))->build())
            ->isIdenticalTo(0);

        $this->alias->get('Foor\Bar')
            ->containsValues(['atoum\atoum', 'hoa\file', 'foo', 'babar' , 'Foox'])
            ->notContainsValues(['Foo\Bar\Qux'])
        ;
    }

    public function testUseAs()
    {
          $source = '<?php
        namespace Foor\Bar;
        use atoum\atoum, babar, Foox;
        use hoa\file;
        use foo as baar;
        ';

        $this
            ->integer((new \Sohapi\Parser\Reader($source))->build())
            ->isIdenticalTo(0);

        $this->alias->get('Foor\Bar')
            ->containsValues(['atoum\atoum', 'hoa\file', 'foo', 'babar' , 'Foox'])
            ->notContainsValues(['Foo\Bar\Qux'])
        ;

        $this->alias->hasAlias('Foor\Bar', 'foo')
            ->isTrue();

        $this->alias->hasAlias('Foor\Bar', 'babar')
            ->isFalse();

        $this->alias->getAlias('Foor\Bar', 'foo')
            ->isIdenticalTo('baar');

        $this->alias->getAlias('Foor\Bar', 'babar')
            ->isIdenticalTo('');
    }
}
