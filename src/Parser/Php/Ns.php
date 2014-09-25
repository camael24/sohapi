<?php
namespace Sohapi\Parser\Php {
    class Ns extends Generic
    {

        public function visit(Element $element, \SplQueue &$handle = null, $eldnah = null)
        {
            $type = null;
            $nodes = $this->getUntilValue($handle, [';', '{'], $type);
            $ns = $this->concatNodes($nodes);

            if ($type === '{') {

                $child = $this->getNodeBetween($handle, '{', '}');
                $before = array();

                echo 'Namespace: ' . $ns . "\n";

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

            } else {
                throw new \Exception("%namespace foo;% ! Missing yet");
            }


        }

        public function dispatch(\SplQueue $child)
        {
            $previous = new \SplQueue();

            if (count($child) === 0)
                return;

            foreach ($child as $key => $value) {
                //echo "\t\t\t\t".$value[0]."\n";
                switch ($value[0]) {
                    // T_USE
                    case 'T_CLASS':
                        (new Classe())->visit($this, $child, $previous);
                        $previous = new \SplQueue();
                        break;
                    default:

                        $child->dequeue();
                        break;
                }
            }
        }

    }
}
