<?php
namespace Sohapi\Parser\Php {
    class Variable extends Generic implements IParser
    {
        public function visit($parent, &$tokens, $handle = array(), $eldnah = null)
        {
            $a          = array_shift($handle);
            $visibilty  = $handle;
            $name       = strval($eldnah[1]);
            $value      = $this->getUntilValue($tokens, ';');

            echo 'Property : '.$this->concat($visibilty).' '.$name.' '.$this->concat($value)."\n";



            $parent->dispatch($tokens);

        }



    }
}
