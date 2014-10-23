<?php
namespace Sohapi\Parser\Php {
    class Comment extends Generic implements IParser
    {
        public function visit($parent, &$tokens, $handle = array(), $eldnah = null)
        {
            echo 'Comment : '.$eldnah[1]."\n";
            $parent->dispatch($tokens);

        }



    }
}
