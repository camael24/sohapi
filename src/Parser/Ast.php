<?php
namespace Sohapi\Parser {
    class Ast {
        private static $_instance = null;
        private static $_last = '';

        private $_classe = array();
        private $_namespace = array();
        private $_properties = array();
        private $_methods = array();
        private $_currentClasse = '';
        private $_currentNamespace = '';

        public static function getInstance()
        {
            if(static::$_instance === null) {
                static::$_instance = new Ast();
            }

            return static::$_instance;
        }

        public static function getLastInstance()
        {
            return static::getInstance();
        }

        public static function getInstances()
        {
            return static::$_instance;
        }

        public function setNamespace ($namespace)
        {
            $this->_namespace[]      = $namespace;
            $this->_currentNamespace = $namespace;
            return $this;
        }

        public function setClasse($classe, $extends = '', $implements = '')
        {
            $a = [
                'class'      => $classe,
                'extends'    => $extends,
                'implements' => $implements
            ];

            if(!in_array($this->_currentNamespace, $this->_namespace)) {
                $this->_namespace[] = $this->_currentNamespace;
            }

            $this->_classe[$this->_currentNamespace][]  = $a;
            $this->_currentClasse                       = $classe;

            return $this;
        }

        public function setProperty($visibility, $isStatic, $name, $default)
        {
            $a = [
                'visibility'    => $visibility,
                'static'        => $isStatic,
                'name'          => $name,
                'default'       => $default
            ];

            $this->_properties[$this->_currentNamespace][$this->_currentClasse][] = $a;

            return $this;
        }

        public function setMethod($visibility, $isStatic, $name, $arguments)
        {
              $a = [
                'visibility'    => $visibility,
                'static'        => $isStatic,
                'name'          => $name,
                'arguments'     => $arguments
            ];

            $this->_methods[$this->_currentNamespace][$this->_currentClasse][] = $a;

            return $this;
        }

        public function getMethods()
        {
            return $this->_methods;
        }

        public function getProperties()
        {
            return $this->_properties;
        }

        public function getClasse()
        {
            return $this->_classe;
        }

        public function getNamespace()
        {
            return $this->_namespace;
        }
    }
}