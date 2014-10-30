<?php
namespace Sohapi\Formatter {
    class Formatter {
        protected $_namespace = array();
        protected $_classe = array();
        protected $_properties = array();
        protected $_methods = array();
        public function __construct()
        {
            $ast                = \Sohapi\Parser\Ast::getInstance();
            $this->_namespace   = $ast->getNamespace();
            $this->_classe      = $ast->getClasse();
            $this->_properties  = $ast->getProperties();
            $this->_methods     = $ast->getMethods();
        }

        public function render()
        {

        }
    }
}
