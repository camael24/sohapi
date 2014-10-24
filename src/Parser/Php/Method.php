<?php
namespace Sohapi\Parser\Php {
    class Method extends Generic implements IParser
    {
        public function visit($parent, &$tokens, $handle = array(), $eldnah = null)
        {
            $visibilty  = $handle;
            $value      = $this->getUntilValue($tokens, '{');
            $c          = array_pop($value);
            $name       = $this->getUntilValue($value, '(');
            $args       = $this->getTokensBetweenValue($value, '(' , ')');


            array_pop($args);
            array_pop($args);



            array_unshift($value, array_pop($name));
            array_unshift($tokens, $c);

            $content = $this->getTokensBetweenValue($tokens, '{' , '}');

            //$this->dump($content); // TODO : Detect throw, return
            //  public function setMethod($visibility, $isStatic, $name, $arguments) {
            \Sohapi\Parser\Ast::getLastInstance()->setMethod(
                $this->concat($visibilty),
                false,
                $this->concat($name),
                (count($args) ===0) ? 'void' : $this->concat($args)
            );
        }



    }
}
