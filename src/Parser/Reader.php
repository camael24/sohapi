<?php
namespace Sohapi\Parser {
    class Reader {

        private $_filename = null;
        private $_source   = '';
        private $_token    = null;

        public function __construct($filename) {
            $this->_filename = $filename;
            $this->_source   = file_get_contents($filename);
            $this->_token    = token_get_all($this->_source);

           foreach ($this->_token as $i => $token) {
                if(is_string($token)) {
                    $token = array('T_STRUCTURAL',trim($token));
                }
                else {
                    $token[0]  = (is_int($token[0])) ? token_name($token[0]) : $token[0];
                }

                $this->_token[$i]   = $token;
            }

        }

        public function getTokens() {
            return $this->_token;
        }

        public function build() {
            //$this->dump();
            foreach ($this->_token as $key => $value) {
                switch($value[0]) {
                    case 'T_NAMESPACE':
                        new Php\Ns($this->_token, $value, $key);
                        break;
                    case 'T_OPEN_TAG':
                    case 'T_WHITSPACE':
                    default:
                }
            }


        }

        public function dump() {
            foreach ($this->_token as $i => $token) {
                echo $token[0];

                if(isset($token[1]))
                    echo "\t" . $token[1];

                echo "\n";
            }
        }
    }
}
