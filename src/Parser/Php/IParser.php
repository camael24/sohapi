<?php
namespace Sohapi\Parser\Php;

interface  IParser
{
    public function visit($parent, &$tokens, $handle, $eldnah = null);

}

