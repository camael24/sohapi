<?php
namespace Sohapi\Parser\Php {
    class Method extends Generic implements IParser
    {
        public function visit($parent, &$tokens, $handle = array(), $eldnah = null)
        {
            $visibilty  = $handle;
            $value      = $this->getUntilValue($tokens, '{');
            $c          = array_pop($value);

            array_unshift($tokens, $c);

            $content = $this->getTokensBetweenValue($tokens, '{' , '}');

            //$this->dump($content); // TODO : Detect throw, return

            echo 'Function : '.$this->concat($visibilty).' '.$this->concat($value)."\n";
        }



    }
}
