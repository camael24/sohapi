<?php
namespace Sohapi\Parser\Php {
    class Method extends Generic implements IParser
    {
        public function visit($parent, &$tokens, $handle = array(), $eldnah = null)
        {
            $visibilty  = $handle;
            $value      = $this->getUntilValue($tokens, ['{', ';']);
            $c          = array_pop($value);
            $name       = $this->getUntilValue($value, '(');
            $args       = $this->getTokensBetweenValue($value, '(' , ')');

            array_pop($args);
            array_pop($args);

            array_unshift($value, array_pop($name));
            array_unshift($tokens, $c);

            $this->_parseArgs($args);

            $content = $this->getTokensBetweenValue($tokens, '{' , '}');
            //$this->dump($content); // TODO : Detect throw, return
            \Sohapi\Parser\Model::getInstance()->setMethod(
                $this->concat($visibilty),
                false,
                $this->concat($name),
                (count($args) ===0) ? 'void' : $args
            );
        }

        protected function _parseArgs($args)
        {
            $var = array();

            //var_dump($args);

            if(count($args) === 0)
                return;

            do {
                $variable = $this->getUntilValue($args, ',');
                array_pop($variable);
                $type = $this->getUntilToken($variable, 'T_VARIABLE');
                array_pop($type);

                $var[] = [
                    'type' => $this->concat($type)
                ];

            }
            while( count($args)  > 0 );

            //var_dump($var);
        }

    }
}
