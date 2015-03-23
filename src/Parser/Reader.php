<?php
namespace Sohapi\Parser;

class Reader
{
    protected $_token = [];

    public function __construct($source)
    {
        $storage      = Model::getInstance();
        $this->_token = token_get_all($source);

        foreach ($this->_token as $key => $value) {
            if (is_array($value)) {
                $type                  = token_name($value[0]);
                $this->_token[$key][0] = $type;
            } else {
                $this->_token[$key] = [
                    0 => 'T_STRUCTURE',
                    1 => trim($value),
                    2 => 0 // LINE UNKNOW
                ];
            }
        }
    }

    public function build()
    {
        (new \Sohapi\Parser\Php\Root())->visit(null, $this->_token, '');

        return count($this->_token);
    }

    public function getTokens()
    {
        return $this->_token;
    }
}
