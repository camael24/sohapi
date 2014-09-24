<?php
    namespace Sohapi\Parser\Php  {
        class Generic {

            private $_tokens = array();
            private $_current = array();
            protected  $_i = 0;


            public function __construct($tokens, $currentNode, $i) {
                $this->_tokens = $tokens;
                $this->_current = $currentNode;
                $this->_i = $i;

                $this->visit();
            }

            public function getTokens() {
                return $this->_tokens;
            }

            public function getToken($id) {
                if(isset($this->_tokens[$id]))
                    return $this->_tokens[$id];

                return null;
            }

            public function getCurrentToken(){
                return $this->_current;
            }

            public function getUntil($value) {
                $return = array();
                for($i = $this->_i; $i < count($this->getTokens()); $i++) {
                        $current = $this->getToken($i);
                        if(isset($current[1]) and $current[1] != $value)
                            $return[] = $current;
                        else
                            return $return;


                }
                return $return;
            }

            public function visit() {

            }
        }
    }

?>