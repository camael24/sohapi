<?php
namespace Sohapi\Parser {
    class Ast {
        private static $_instance = array();
        private static $_last = '';


        public static function getInstance($key) {
            if(isset(static::$_instance[$key]) === false) {
                static::$_instance[$key] = new Ast($key);
            }

            static::$_last = $key;

            return static::$_instance[$key];
        }

        public static function getLastInstance() {
            return static::getInstance(static::$_last);
        }

        public static function getInstances() {
            return static::$_instance;
        }

        private function __construct($key) {

        }

        public function setNamespace ($namespace) {
            return $this;
        }

        public function setClasse($classe) {
            return $this;
        }

        public function setExtends($list) {
            return $this;
        }

        public function setImplments($list) {
            return $this;
        }

        public function setProperty($visibility, $isStatic, $name, $default) {
            return $this;
        }

        public function setMethod($visibility, $isStatic, $name, $arguments) {
            return $this;
        }

        public function render() {

        }


    }
}