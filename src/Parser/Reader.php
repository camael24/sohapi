<?php
namespace Sohapi\Parser {

    class Reader
    {
        protected $_token = array();

        public function __construct($uri)
        {
            $file           = file_get_contents($uri);
            $this->_token   = token_get_all($file);

            foreach ($this->_token as $key => $value) {
                if (is_array($value)) {
                    $type                   = token_name($value[0]);
                    $this->_token[$key][0]  = $type;
                } else {
                    $this->_token[$key] = array(
                        0 => 'T_STRUCTURE',
                        1 => trim($value),
                        2 => 0 // LINE UNKNOW
                    );
                }
            }
        }

        public function build()
        {
            echo 'Total token : '.count($this->_token)."\n";

            $root = new \Sohapi\Parser\Php\Root();
            $root->visit(null, $this->_token, '');

            echo "\n".'Left token : '.count($this->_token)."\n";
        }
    }
}
