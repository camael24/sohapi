<?php
namespace Sohapi\Parser\Php {
    class Variable extends Generic implements IParser
    {
        public function visit($parent, &$tokens, $handle = array(), $eldnah = null)
        {

            $visibilty  = $handle;
            $name       = strval($eldnah[1]);
            $value      = $this->getUntilValue($tokens, ';');

//setProperty($visibility, $isStatic, $name, $default) {

            \Sohapi\Parser\Ast::getLastInstance()->setProperty($this->concat($visibilty) , false, $name, $this->concat($value));

            //echo 'Property : '.$this->concat($visibilty).' '.$name.' '.$this->concat($value)."\n";



            $parent->dispatch($tokens);

        }



    }
}
