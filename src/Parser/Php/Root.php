<?php
namespace Sohapi\Parser\Php {
    class Root extends Generic implements IParser
    {
        public function visit($parent, &$tokens, $handle = array(), $eldnah = null)
        {
            $this->dispatch($tokens, $handle, $eldnah);
        }

        public function dispatch(&$tokens,  $handle = array(), $eldnah = null)
        {
            $before = array();

            while (($token = $this->consume($tokens)) !== null) {
                switch ($token[0]) {
                    case 'T_COMMENT':
                    case 'T_DOC_COMMENT':
                        (new Comment())->visit($this, $tokens, $before, $token);
                    case 'T_NAMESPACE':
                        (new NS())->visit($this, $tokens, $before, $eldnah);
                        $before = array();
                    break;
                    case 'T_CLASS':
                        (new Classe())->visit($this, $tokens, $before, $eldnah);
                        $before = array();
                    default:
                        $before[] = $token;
                    break;
                }
            }
        }

    }
}
