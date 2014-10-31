<?php
namespace Sohapi\Parser\Php {
    class Comment extends Generic implements IParser
    {
        public function visit($parent, &$tokens, $handle = array(), $eldnah = null)
        {
            \Sohapi\Parser\Ast::getInstance()->setComment($eldnah[1]);

            $parent->dispatch($tokens);
        }
    }
}
