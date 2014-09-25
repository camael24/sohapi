<?php
namespace Sohapi\Parser\Php {
    class Method extends Generic
    {
        public function visit(Element $element, \SplQueue &$handle = null, $eldnah = null)
        {

            var_dump(__CLASS__ . '#' . __LINE__);
            $name = $this->getUntilValue($handle, '(');
            var_dump(__CLASS__ . '#' . __LINE__);
            $name = $this->getListData($name);
            $argument = $this->getNodeBetween($handle, '(', ')');
            var_dump(__CLASS__ . '#' . __LINE__);
            $content = $this->getNodeBetween($handle, '{', '}'); // This is Drop !!!! TODO : Get the return value !
            var_dump(__CLASS__ . '#' . __LINE__);
            $previous = implode(' ', $this->getListData($eldnah));
            $argument = $this->concatNodes($argument);

            echo "\t\t\t\t\t\t" . implode(' ', $name) . "\n";

            //print_r($handle);
            var_dump(__CLASS__ . '#' . __LINE__);
            $element->dispatch($handle);
            var_dump(__CLASS__ . '#' . __LINE__);
        }

        public function readArgument($args)
        {

            $e[] = array();

            if (count($args) > 0) {
                foreach ($args as $arg) {
                    $a = $this->getUntilValue($args, ',');


                }
            }

            return $e;

        }

    }
}
