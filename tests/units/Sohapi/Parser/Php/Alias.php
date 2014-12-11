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
    }
}
