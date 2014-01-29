<?php
/**
 * Created by PhpStorm.
 * User: Camael24
 * Date: 24/01/14
 * Time: 10:51
 */
namespace Foo\Bar\Qux {
    class Gordon
    {

    }

    interface G
    {

    }
}
namespace Test {
    use Foo\Bar\Qux\G;
    use Foo\Bar\Qux\Gordon;

    class D extends Gordon implements G
    {
        protected function dFunction()
        {

        }

    }

    interface I
    {

    }

    interface B
    {

    }

    class C extends D implements I, B
    {

        const I = 1;

        const F = 2;
        /**
         * Bar foo
         * @var null
         */
        public static $_instance = null;
        private $_private = 'foo';
        protected $_protected = 'bar';
        public $_public = 'Waza';

        public function __construct($foo, $bar)
        {


        }

        /**
         * @param \Stdclass $foo
         */
        public function bo(\Stdclass &$foo)
        {

        }

        protected function baz($a = 'fpp')
        {

        }

        private function b($a, Gordon $d = null)
        {

        }

        final public function fFinal(Array $foo)
        {

        }

        final protected function pFinal(\Closure $f)
        {

        }

        public static function get()
        {

        }

        final public static function set($i, $v)
        {

        }


    }
}
 