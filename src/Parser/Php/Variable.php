<?php
namespace Sohapi\Parser\Php {
    class Variable extends Generic implements IParser
    {
        public function visit($parent, &$tokens, $handle = array(), $eldnah = null)
        {

            $visibilty  = $handle;
            $name       = strval($eldnah[1]);
            $value      = $this->getUntilValue($tokens, ';');

            // TODO : Static classe
            \Sohapi\Parser\Model::getInstance()->setProperty($this->concat($visibilty) , false, $name, $this->concat($value));

            $parent->dispatch($tokens);
        }

    }
}
