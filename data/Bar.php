<?php
    namespace Foo\Bar {
        class Hello extends Bar implements Qux,Babar {

            public $_foo = 'bra';
            protected $options = array(
                array('section', '\Hoa\Console\GetOption::REQUIRED_ARGUMENT', 's'),
                array('mail',    '\Hoa\Console\GetOption::REQUIRED_ARGUMENT', 'm'),
                array('help',    '\Hoa\Console\GetOption::NO_ARGUMENT',       'h'),
                array('help',    '\Hoa\Console\GetOption::NO_ARGUMENT',       '?')
            );
            protected $f = array(
                'o' => 'foo',
                'a' => true
            );


            public function __construct($foo = true, $false = false, $null = null) {

            }

            private static function myFunctionIsPri($a = 'foo', $b = "bar") {

            }

            private function foo($hhhh = array()) {

            }

            private function bar($a = self::foo) {

            }

        }

    }

?>