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
                    case 'T_INTERFACE':
                        (new Iface())->visit($this, $tokens, $before, $eldnah);
                        $before = array();
                    break;
                    case 'T_ABSTRACT':
                        (new Abs())->visit($this, $tokens, $before, $eldnah);
                        $before = array();
                    break;
                    case 'T_CLASS': // TODO : Interface Traits Abstract
                        (new Classe())->visit($this, $tokens, $before, $eldnah);
                        $before = array();
                    break;
                    default:
                        $before[] = $token;
                    break;
                }
            }
        }

    }
}
