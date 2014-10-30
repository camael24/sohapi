<?php
namespace Sohapi\Parser\Php {
    class Ns extends Generic implements IParser
    {
        public function visit($parent, &$tokens, $handle = array(), $eldnah = null)
        {
            $buffer     = $this->getUntilValue($tokens, ['{', ';']);
            $separator  = array_pop($buffer);
            $ns         = trim($this->concat($buffer));

            \Sohapi\Parser\Ast::getInstance()->setNamespace($ns);

            if ($separator[1] === '{') {
                array_unshift($tokens, $separator);
                $content = $this->getTokensBetweenValue($tokens, '{' , '}');
                $parent->dispatch($content);
            }
            if ($separator[1] === ';')
                throw new \Exception("Not support yet", 1);

        }

    }
}
