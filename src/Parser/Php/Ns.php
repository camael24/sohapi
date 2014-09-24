<?php
namespace Sohapi\Parser\Php {
    class Ns extends Generic
    {

        public function visit(Element $element, \SplQueue &$handle = null, $eldnah = null)
        {
            $nodes  = $this->getUntilValue($handle, '{');
            $ns     = $this->concatNodes($nodes);
            $child  = $this->getNodeBetween($handle, '{' , '}');
            $before = array();

            echo 'Namespace: '.$ns."\n";

            foreach ($child as $key => $value) {
                switch ($value[0]) {
                    // T_USE
                    case 'T_CLASS':
                        (new Classe())->visit($this, $child);
                        break;
                    default:
                        $child->dequeue();
                        break;
                }
            }

           //var_dump('e'); // TODO : segfault without oO
        }
    }
}
