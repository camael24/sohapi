<?php
namespace Sohapi\Parser\Php;

class Abs extends Generic implements IParser
{
    public function visit($parent, &$tokens, $handle = [], $eldnah = null)
    {
        $a = $this->getUntilToken($tokens, 'T_CLASS');
        array_shift($tokens);

        (new Classe(true))->visit($this, $tokens, $handle, $eldnah);
    }
}

