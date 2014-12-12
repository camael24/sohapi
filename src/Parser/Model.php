<?php
namespace Sohapi\Parser {
    class Model
    {
        private static $_instance = null;
        private static $_last = '';

        private $_classe = array();
        private $_interface = array();
        private $_abstract = array();
        private $_namespace = array();
        private $_properties = array();
        private $_methods = array();
        private $_comment = null;
        private $_use = array();
        private $_alias = array();
        private $_currentClasse = '';
        private $_currentNamespace = '';
        private static $_debug = false;

        public static function getInstance()
        {
            if (static::$_instance === null) {
                static::$_instance = new Model();
            }

            return static::$_instance;
        }

        public function __construct()
        {

        }

        public static function enableDebug()
        {
            static::$_debug = true;
        }

        public function setComment($comment)
        {
            $this->_comment = $comment;
        }

        public function setNamespace($namespace)
        {
            $this->_namespace[]      = $namespace;
            $this->_currentNamespace = $namespace;
            $this->dump('> NS '.$namespace);
            return $this;
        }

        public function setUse($classe, $as = '')
        {
            $a = [
                'class'     => $classe,
                'as'        => $as
            ];

            if (!in_array($this->_currentNamespace, $this->_namespace)) {
                $this->_namespace[] = $this->_currentNamespace;
            }

            $this->_use[$this->_currentNamespace][]  = $a;
            $this->dump('> Use '.$classe.' AS '.$as);
            return $this;
        }

        public function setAlias($alias, $classe) {

            if($alias[0] !== '/' or $alias[0] !== '\\')
                $alias = '\\'.$alias;

            if($classe[0] !== '/' or $classe[0] !== '\\')
                $classe = '\\'.$classe;


            $this->_alias[$classe] = $alias;

            return $this;
        }

        public function setClasse($classe, $extends = '', $implements = '')
        {
            $a = [
                'class'      => $classe,
                'extends'    => $extends,
                'implements' => $implements,
                'comment'    => $this->_comment
            ];
            $this->_comment = null;

            if (!in_array($this->_currentNamespace, $this->_namespace)) {
                $this->_namespace[] = $this->_currentNamespace;
            }

            $this->_classe[$this->_currentNamespace][]  = $a;
            $this->_currentClasse                       = $classe;
            $this->dump('> Class '.$classe);
            return $this;
        }

        public function setInterface($classe, $extends = '')
        {
            $a = [
                'interface'  => $classe,
                'extends'    => $extends,
                'comment'    => $this->_comment
            ];

            $this->_comment = null;

            if (!in_array($this->_currentNamespace, $this->_namespace)) {
                $this->_namespace[] = $this->_currentNamespace;
            }

            $this->_interface[$this->_currentNamespace][]  = $a;
            $this->_currentClasse                          = $classe;
            $this->dump('> Interface '.$classe);
            return $this;
        }

        public function setAbstract($classe, $extends = '', $implements = '')
        {
            $a = [
                'abstract'   => $classe,
                'extends'    => $extends,
                'implements' => $implements,
                'comment'    => $this->_comment
            ];

            $this->_comment = null;
            if (!in_array($this->_currentNamespace, $this->_namespace)) {
                $this->_namespace[] = $this->_currentNamespace;
            }

            $this->_abstract[$this->_currentNamespace][]  = $a;
            $this->_currentClasse                          = $classe;
            $this->dump('> Abstract '.$classe);
            return $this;
        }

        public function setProperty($visibility, $isStatic, $name, $default)
        {
            $default = trim(substr($default, 1, -1));
            $a = [
                'visibility'    => $visibility,
                'static'        => $isStatic,
                'name'          => $name,
                'default'       => $default,
                'comment'       => $this->_comment
            ];
            $this->_comment = null;
            $this->_properties[$this->_currentNamespace][$this->_currentClasse][] = $a;
            $this->dump('> Property '.$name);
            return $this;
        }

        public function setMethod($visibility, $isStatic, $name, $arguments)
        {
              $a = [
                'visibility'    => $visibility,
                'static'        => $isStatic,
                'name'          => $name,
                'arguments'     => $arguments,
                'comment'       => $this->_comment
            ];
            $this->_comment = null;
            $this->_methods[$this->_currentNamespace][$this->_currentClasse][] = $a;
            $this->dump('> Method '.$name);
            return $this;
        }


        public function getAll()
        {
            return [
                'namespace' => $this->getNamespace(),
                'use' => $this->getUse(),
                'classe' => $this->getClasse(),
                'interface' => $this->getInterface(),
                'abstract' => $this->getAbstract(),
                'methods' => $this->getMethods(),
                'properties' => $this->getProperties(),
                'alias' => $this->getAlias()
            ];
        }

        public function getNamespace()
        {
            return $this->_namespace;
        }

        public function getUse()
        {
            return $this->_use;
        }

        public function getClasse()
        {
            return $this->_classe;
        }

        public function getInterface()
        {
            return $this->_interface;
        }

        public function getAbstract()
        {
            return $this->_abstract;
        }

        public function getMethods()
        {
            return $this->_methods;
        }

        public function getProperties()
        {
            return $this->_properties;
        }

        public function getAlias()
        {
            return $this->_alias;
        }

        public function dump($str){
            if(static::$_debug === true)
                echo $str."\n";
        }

    }
}
