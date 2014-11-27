<?php
namespace Sohapi\Formatter {
    class Formatter
    {
        protected $_namespace = array();
        protected $_classe = array();
        protected $_properties = array();
        protected $_methods = array();
        protected $_interface = array();
        protected $_abstract = array();
        protected $_use = array();
        protected $_arguments = array();

        public function __construct($arguments = array())
        {
            $ast                = \Sohapi\Parser\Model::getInstance();
            $this->_namespace   = $ast->getNamespace();
            $this->_classe      = $ast->getClasse();
            $this->_interface   = $ast->getInterface();
            $this->_abstract    = $ast->getAbstract();
            $this->_properties  = $ast->getProperties();
            $this->_methods     = $ast->getMethods();
            $this->_use         = $ast->getUse();
            $this->_alias       = $ast->getAlias();
            $this->_arguments   = $arguments;
        }

        public function getArgument($key)
        {
            if (isset($this->_arguments[$key])) {
                return $this->_arguments[$key];
            }

            return null;
        }

        protected function resolveClass($classe, $ns)
        {
            $classe = trim($classe);
            $ns     = trim($ns);

            if(strlen($classe) === 0)

                return '';
            // A. FQCN
             if($classe[0] === '\\')

                return $this->_gns($classe);

            // B. Class
            // 1. Alias
            if(isset($this->_use[$ns]))
                foreach ($this->_use[$ns] as $u)
                    if($u['as'] !== '' and $u['as'] === $classe)

                        return $this->_gns($u['class']);

            // 2. Use
            if(isset($this->_use[$ns]))
                foreach ($this->_use[$ns] as $u)
                    if($this->extractClassname($u['class']) === $classe)

                        return $this->_gns($u['class']);

            // 3. class_alias



            // 4. Ns courant
            return $this->_gns($ns.'\\'.$classe);
        }

        protected function _gns($ns)
        {
            if(isset($this->_alias[$ns]))
                $ns = $this->_alias[$ns];


            if($ns[0] !== '\\')

                return '\\'.$ns;

            return $ns;
        }

        protected function extractClassname($class)
        {
            $last = strrpos($class, '\\');

            return substr($class, $last + 1);
        }

        protected function copyDirectory($source, $destination)
        {
            if (is_dir($source)) {
                if (!is_dir($destination)) {
                    mkdir($destination);
                }

                $objects = scandir($source);
                if (sizeof($objects) > 0) {
                    foreach ($objects as $file) {
                        if ($file == "." || $file == "..")
                            continue;
                        // go on
                        if (is_dir($source . DS . $file)) {
                            $this->copyDirectory($source . DS . $file, $destination . DS . $file);
                        } else {
                            //if (!is_file($destination . DS . $file)) {
                                copy($source . DS . $file, $destination . DS . $file);
                            //}
                        }
                    }
                }

                return true;
            } elseif (is_file($source)) {
                return copy($source, $destination);
            } else {
                return false;
            }
        }

        public function render()
        {

        }
    }
}
