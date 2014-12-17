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

            protected function fppp($a = "babar") {
                return $a;
            }

            private function unset($aaa, $a ="ab", $nn = true ){

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
            ->containsValues(['get', 'set', 'unset', 'g', 'b', 'fppp'])
            ->notContainsValues(['hello'])
        ;

        $this->method->args('', 'Foo' , 'get')
            ->string['get']['arguments'][0]->isIdenticalTo('')
            ->boolean['get']['static']->isFalse()
            ->string['get']['visibility']->isIdenticalTo('public')

            ->string['set']['arguments'][0]->isIdenticalTo('$a = null')
            ->string['set']['visibility']->isIdenticalTo('protected')

            ->string['unset']['arguments'][0]->isIdenticalTo('$aaa')
            ->string['unset']['arguments'][1]->isIdenticalTo('$a ="ab"')
            ->string['unset']['visibility']->isIdenticalTo('private')

            ->string['fppp']['arguments'][0]->isIdenticalTo('$a = "babar"')

            ->string['g']['arguments'][0]->isIdenticalTo('')
            ->boolean['g']['static']->isTrue()
            ->string['g']['visibility']->isIdenticalTo('public')
        ;

    }

    public function testMethodWithComma()
    {
        $source = '<?php class Foo {protected function fppp($b, $a = "ba,bar") {return $a;}};';

        $this
            ->integer((new \Sohapi\Parser\Reader($source))->build())
            ->isIdenticalTo(0);

        $this->method->get('', 'Foo')
            ->containsValues(['fppp'])
            ->notContainsValues(['hello'])
        ;

        $this->method->args('', 'Foo' , 'get')
            ->string['fppp']['arguments'][0]->isIdenticalTo('$b')
            ->string['fppp']['arguments'][1]->isIdenticalTo('$a = "ba,bar"')
            ->string['fppp']['visibility']->isIdenticalTo('protected')
        ;

    }
}
