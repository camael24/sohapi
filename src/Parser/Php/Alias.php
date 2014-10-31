<?php
namespace Sohapi\Parser\Php {
    class Alias extends Generic implements IParser
    {
        public function visit($parent, &$tokens, $handle = array(), $eldnah = null)
        {
            $use = $this->getUntilValue($tokens, ';');
            $elm = array();

            do {
                $elm[] = $this->getUntilValue($use, ',');

            } while (count($use) > 0);

            foreach ($elm as $e) {
                $this->parse($e);
            }

        }

        protected function parse($use)
        {
            $last = array_pop($use);

            $name = '';
            $as   = '';

            if ($this->tokenExists($use, 'T_AS') === true) {
                $name = $this->getUntilToken($use, 'T_AS');
                array_pop($name);

                $name = $this->concat($name);
                $as   = $this->concat($use);
            } else {
                $name = $this->concat($use);
            }
            \Sohapi\Parser\Ast::getInstance()->setUse($name, $as);

        }

    }
}
