<?php
namespace Foo\Bar;
    class Foobar extends Bar implements A,B,C
    {
        private static $_foo = array();
        private $_bar = array();
        private $_a = array('a' , 'b' => array('ab', 'bb'));
        public function foo($a, $b=array(), $c = null)
        {
        }

        public static function bar() {}
        public function oooo() {}
    }
