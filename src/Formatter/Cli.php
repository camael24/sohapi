<?php
namespace Sohapi\Formatter {
    class Cli {

        public function __construct() {
            $ast                = \Sohapi\Parser\Ast::getInstance();
            $this->_namespace   = $ast->getNamespace();
            $this->_classe      = $ast->getClasse();
            $this->_properties  = $ast->getProperties();
            $this->_methods     = $ast->getMethods();
        }

        public function render() {

            foreach ($this->_namespace as $namespace) {
                echo '------------- NS -------------'."\n";
                echo 'Namespace : '.(($namespace === '') ? 'ROOT' : $namespace)."\n";

                foreach ($this->_classe[$namespace] as $classe) {
                    echo '------------- CLASSE -------------'."\n";
                    echo 'Classe : '.$classe['class']."\n";
                    echo "\t".'Extends : '.$classe['extends']."\n";
                    echo "\t".'Implements : '.$classe['implements']."\n";

                    echo 'Properties : '."\n";
                    $classe = $classe['class'];
                    if(isset($this->_properties[$namespace][$classe])){
                        foreach ($this->_properties[$namespace][$classe] as $property) {
                            echo "\t".$property['visibility'].' '.(($property['static'] === true) ? 'static' : '').' '.$property['name'].' '.$property['default']."\n";
                        }
                    }

                    echo 'Methods : '."\n";
                    if(isset($this->_methods[$namespace][$classe])){
                        foreach ($this->_methods[$namespace][$classe] as $method) {
                            echo "\t".$method['visibility'].' '.(($method['static'] === true) ? 'static' : '').' '.$method['name'].' ('.$method['arguments'].')'."\n";
                        }
                    }

                    echo '------------- CLASSE -------------'."\n";

                }
                echo '------------- NS -------------'."\n";
            }
        }
    }
}