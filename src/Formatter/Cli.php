<?php
namespace Sohapi\Formatter {
    class Cli extends Formatter
    {
        public function render()
        {
            foreach ($this->_namespace as $namespace) {
                echo '------------- NS -------------'."\n";
                echo 'Namespace : '.(($namespace === '') ? 'ROOT' : $namespace)."\n";

                if(isset($this->_classe[$namespace])) {
                    foreach ($this->_classe[$namespace] as $classe) {
                        echo '------------- CLASSE -------------'."\n";
                        echo 'Classe : '.$classe['class']."\n";
                        echo "\t".'Extends : '.$classe['extends']."\n";
                        echo "\t".'Implements : '.$classe['implements']."\n";

                        echo 'Properties : '."\n";
                        $classe = $classe['class'];
                        if (isset($this->_properties[$namespace][$classe])) {
                            foreach ($this->_properties[$namespace][$classe] as $property) {
                                echo "\t".$property['visibility'].' '.(($property['static'] === true) ? 'static' : '').' '.$property['name'].' '.$property['default']."\n";
                            }
                        }

                        echo 'Methods : '."\n";
                        if (isset($this->_methods[$namespace][$classe])) {
                            foreach ($this->_methods[$namespace][$classe] as $method) {
                                echo "\t".$method['visibility'].' '.(($method['static'] === true) ? 'static' : '').' '.$method['name'].' ('.$method['arguments'].')'."\n";
                            }
                        }

                        echo '------------- !CLASSE -------------'."\n";

                    }
                }
                if(isset($this->_interface[$namespace])) {
                    foreach ($this->_interface[$namespace] as $classe) {
                        echo '------------- INTERFACE -------------'."\n";
                        echo 'Interface : '.$classe['interface']."\n";
                        echo "\t".'Extends : '.$classe['extends']."\n";

                        echo 'Methods : '."\n";
                        $classe = $classe['interface'];
                        if (isset($this->_methods[$namespace][$classe])) {
                            foreach ($this->_methods[$namespace][$classe] as $method) {
                                echo "\t".$method['visibility'].' '.(($method['static'] === true) ? 'static' : '').' '.$method['name'].' ('.$method['arguments'].')'."\n";
                            }
                        }

                        echo '------------- !INTERFACE -------------'."\n";

                    }
                }

                if(isset($this->_abstract[$namespace])) {
                    foreach ($this->_abstract[$namespace] as $classe) {
                        echo '------------- ABSTRACT -------------'."\n";
                        echo 'Abstract : '.$classe['abstract']."\n";
                        echo "\t".'Extends : '.$classe['extends']."\n";

                        echo 'Methods : '."\n";
                        $classe = $classe['abstract'];
                        if (isset($this->_methods[$namespace][$classe])) {
                            foreach ($this->_methods[$namespace][$classe] as $method) {
                                echo "\t".$method['visibility'].' '.(($method['static'] === true) ? 'static' : '').' '.$method['name'].' ('.$method['arguments'].')'."\n";
                            }
                        }

                        echo '------------- !ABSTRACT -------------'."\n";

                    }
                }
                echo '------------- !NS -------------'."\n";
            }
        }
    }
}
