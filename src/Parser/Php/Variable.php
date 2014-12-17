<?php
namespace Sohapi\Parser\Php {
    class Variable extends Generic implements IParser
    {
        public function visit($parent, &$tokens, $handle = array(), $eldnah = null)
        {

            $visibilty  = $handle;
            $name       = strval($eldnah[1]);
            $value      = $this->getUntilValue($tokens, ';');

            $visibilty = $this->concat($visibilty);
            $isStatic  = false;

            if(preg_match('#static#', $visibilty)){
                $isStatic   = true;
                $visibilty  = str_replace('static', '', $visibilty);
                $visibilty  = trim($visibilty);
            }

            if($visibilty === '')
                $visibilty = 'public';

            \Sohapi\Parser\Model::getInstance()->setProperty($visibilty , $isStatic, $name, $this->concat($value));
            $parent->dispatch($tokens);
        }

    }
}
