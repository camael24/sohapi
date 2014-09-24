<?php
namespace Sohapi\Parser\Php {
    class Variable extends Generic
    {
        public function visit(Element $element, \SplQueue &$handle = null, $eldnah = null)
        {

            $name     = $this->getUntilValue($handle, '=');
            $name     = $this->getListData($name);
            //$e     = $handle->dequeue();
            $value    = $this->getUntilValue($handle, ';');
            $value    = $this->concatNodes($value);
            $previous = implode(' ',$this->getListData($eldnah));

            echo $previous."\t". implode(' ', $name).' '.$value."\n";

            //var_dump('Count : '.count($handle));

            $element->dispatch($handle);

        }

    }
}
