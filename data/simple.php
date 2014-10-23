<?php
namespace Foo\Bar {
    class Foobar extends Bar implements A,B,C
    {
        /**
        * Foo
        * Bar
        */
        public static function bar($a = 'D') {}
        // Foo
        public function ooooa() {}
        /* Bar */
        public function oooo() {}

        public $_data = null;
        private $_paths = null;
        private $_inherits = array();
        private $_blocks = array();
        private $_blocknames = array();
        private $_file = '';
        private $_headers = array();
        protected $_helpers = array();
/**
*
*/
        public function __construct($path = null)
        {
            $this->setPath($path);
            $this->setData();
        }

    }
}
