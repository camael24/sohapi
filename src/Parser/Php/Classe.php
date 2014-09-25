<?php
namespace Sohapi\Parser\Php {
    class Classe extends Generic
    {
        public function visit(Element $element, \SplQueue &$handle = null, $eldnah = null)
        {
            $c            = $this->getUntilToken($handle, 'T_CLASS');
            $nodes        = $this->getUntilValue($handle, '{');
            $classname    = array();
            $extends      = array();
            $implements   = array();
            $classname    = $this->getUntilToken($nodes, array('T_EXTENDS' , 'T_IMPLEMENTS'));
            $classname    = $this->concatNodes($classname);
            $rest         = $this->extractToken($nodes,  array('T_EXTENDS' , 'T_IMPLEMENTS'));

            echo 'Classname: '.$classname."\n";

            foreach ($rest as $key => $value) {
                if ($key === 'T_EXTENDS') {
                    echo 'Extends:    '.implode(',', $this->getListData($value))."\n";
                } elseif ($key === 'T_IMPLEMENTS') {
                    echo 'Implements: '.implode(',', $this->getListData($value))."\n";
                }
            }
            echo "\n";
            $child    = $this->getNodeBetween($handle, '{' , '}');

            $left = $this->dispatch($child);
            echo 'Wouzou ?'."\n";
        }

        public function dispatch(\SplQueue $child)
        {
            $previous = new \SplQueue();

            if(count($child) === 0)
                return;

            foreach ($child as $key => $value) {
                //echo "\t\t\t\t".$value[0]."\n";
                switch ($value[0]) {
                    // T_USE
                    case 'T_VARIABLE':
                        $var = new Variable();
                        $var->visit($this, $child, $previous);
                        $previous = new \SplQueue();
                        break;
                    case 'T_FUNCTION':
                        $func = new Method();
                        $func->visit($this, $child, $previous);
                        $previous = new \SplQueue();
                        break;
                    case 'T_STATIC':
                    case 'T_PUBLIC':
                    case 'T_PROTECTED':
                    case 'T_PRIVATE':
                        $previous->enqueue($child->dequeue());
                        break;
                    default:
                        $child->dequeue();
                        break;
                }
            }
            if(empty($child) === true)
                return null;

            echo 'In Dispatch after treatment'."\n";
        }
    }
}
