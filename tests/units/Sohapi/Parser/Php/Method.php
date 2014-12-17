<?php

namespace tests\units\Sohapi\Parser\Php;

use mageekguy\atoum;

class Method extends \Sohtest\Asserters\Test
{
    public function testMethod()
    {
        $source = '<?php
        class Foo {
            public function get() {
                return "dddd";
            }

            protected function set($a = null) {
                return $a;
            }

            private function unset($aaa, $a = "aa"){

            }

            public static function g() {

            }

            static public function b() {

            }
        };';

        $this
            ->integer((new \Sohapi\Parser\Reader($source))->build())
            ->isIdenticalTo(0);

        $this->method->get('', 'Foo')
            ->containsValues(['get', 'set', 'unset', 'g', 'b'])
            ->notContainsValues(['hello'])
        ;

        $this->method->args('', 'Foo' , 'get')
            ->string['get']['arguments'][0]->isIdenticalTo('')
            ->string['set']['arguments'][0]->isIdenticalTo('$a = null')
            ->string['unset']['arguments'][0]->isIdenticalTo('$aaa')
            //->string['unset']['arguments'][1]->isIdenticalTo('$a ="aa"') // TODO : Bug
            ->string['g']['arguments'][0]->isIdenticalTo('')
            ->boolean['g']['static']->isTrue() // TODO : Bug
            ->boolean['get']['static']->isFalse()
            ->string['get']['visibility']->isIdenticalTo('public')
            ->string['set']['visibility']->isIdenticalTo('protected')
            ->string['unset']['visibility']->isIdenticalTo('private')
            ->string['g']['visibility']->isIdenticalTo('public')
        ;

    }
}
