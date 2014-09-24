<?php
    namespace Sohapi\Parser\Php  {
        class Ns extends Generic{
            public function visit() {

                    $element = $this->getUntil('{');
                    print_r($element);
            }
        }
    }

?>