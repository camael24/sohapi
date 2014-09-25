<?php
namespace Sohapi\Parser\Php {
    class Variable extends Generic
    {
        public function visit(Element $element, \SplQueue &$handle = null, $eldnah = null)
        {
            $name      = $this->getUntilValue($handle, '=');
            $name      = $this->getListData($name);
            $value     = $this->getUntilValue($handle, ';', $type);
            $semicolon = $handle->dequeue();
            $value     = $this->concatNodes($value);
            $previous  = implode(' ',$this->getListData($eldnah));

            echo $previous."\t". implode(' ', $name).' '.$value."\n";
            $element->dispatch($handle);

        }

    }
}
