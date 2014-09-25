<?php
namespace Sohapi\Parser\Php {
    class Variable extends Generic
    {
        public function visit(Element $element, \SplQueue &$handle = null, $eldnah = null)
        {
            var_dump(__CLASS__.'#'.__LINE__);
            $name      = $this->getUntilValue($handle, '=');
            var_dump(__CLASS__.'#'.__LINE__);
            $name      = $this->getListData($name);
            var_dump(__CLASS__.'#'.__LINE__);
            $value     = $this->getUntilValue($handle, ';', $type);
            var_dump(__CLASS__.'#'.__LINE__);
            $semicolon = $handle->dequeue();
            var_dump(__CLASS__.'#'.__LINE__);
            $value     = $this->concatNodes($value);
            var_dump(__CLASS__.'#'.__LINE__);
            $previous  = implode(' ',$this->getListData($eldnah));

            //echo $previous."\t". implode(' ', $name).' '.$value."\n";
            $element->dispatch($handle);
            var_dump(__CLASS__.'#'.__LINE__);
        }

    }
}
