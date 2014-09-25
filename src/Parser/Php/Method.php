<?php
namespace Sohapi\Parser\Php {
    class Method extends Generic
    {
        public function visit(Element $element, \SplQueue &$handle = null, $eldnah = null)
        {

            $name     = $this->getUntilValue($handle, '(');
            $name     = $this->getListData($name);
            $argument = $this->getNodeBetween($handle, '(' , ')');
            $content  = $this->getNodeBetween($handle, '{' , '}'); // This is Drop !!!! TODO : Get the return value !
            $previous = implode(' ',$this->getListData($eldnah));
            $argument = $this->concatNodes($argument);

            print_r($handle);

            $element->dispatch($handle);
        }

        public function readArgument($args) {

            $e[] = array();

            if(count($args) > 0){
                foreach($args as $arg) {
                    $a = $this->getUntilValue($args, ',');


                }
            }

            return $e;

        }

    }
}
