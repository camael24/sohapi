<?php
namespace Sohapi\Parser\Php {
    class Root extends Generic
    {
        public function visit(Element $element, \SplQueue &$handle = null, $eldnah = null)
        {
            $before = array();

            foreach ($handle as $key => $value) {

                switch ($value[0]) {
                    case 'T_NAMESPACE':
                        $handle->dequeue();
                        (new Ns())->visit($this, $handle);
                        break;
                    default:
                        $handle->dequeue();
                        break;
                }
            }
        }

    }
}
