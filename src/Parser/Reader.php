<?php
namespace Sohapi\Parser {
    class Reader implements Php\Element
    {
        private $_filename = null;
        private $_source   = '';
        private $_token    = null;

        public function __construct($filename)
        {
            $this->_filename = realpath($filename);
            $this->_source   = file_get_contents($filename);
            $token           = token_get_all($this->_source);
            $this->_token    = new \SplQueue();

           foreach ($token as $i => $t) {
                if (is_string($t)) {
                    $t = array('T_STRUCTURAL',trim($t));
                } else {
                    $t[0]  = (is_int($t[0])) ? token_name($t[0]) : $t[0];
                }

                $this->_token->enqueue($t);
            }

        }

        public function getTokens()
        {
            return $this->_token;
        }

        public function build()
        {
            $token = $this->getTokens();
            $root  = new Php\Root();

            echo 'Total token ('.count($token).')'."\n";

            $root->visit($this, $token);
            echo "\n".'Left token in stack ('.count($token).')'."\n";
        }

        public function dump()
        {
            foreach ($this->_token as $i => $token) {
                echo $token[0];

                if(isset($token[1]))
                    echo "\t" . $token[1];

                echo "\n";
            }
        }
    }
}
