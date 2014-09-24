<?php
namespace Sohapi\Parser\Php {
    class Method extends Generic
    {
        public function visit(Element $element, \SplQueue &$handle = null, $eldnah = null)
        {

            $name     = $this->getUntilValue($handle, '(');
            $name     = $this->getListData($name);
            $argument = $this->getNodeBetween($handle, '(' , ')');
            $content  = $this->getNodeBetween($handle, '{' , '}');
            $previous = implode(' ',$this->getListData($eldnah));

            echo $previous."\t". implode(' ', $name).' ()'."\n";

            $element->dispatch($handle);

        }

    }
}
