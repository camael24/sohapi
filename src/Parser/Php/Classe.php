<?php
namespace Sohapi\Parser\Php {
    class Classe extends Generic implements IParser
    {
        public function visit($parent, &$tokens, $handle = array(), $eldnah = null)
        {
            $deps       = $this->getUntilValue($tokens, '{');
            $separator  = array_pop($deps);

            array_unshift($tokens, $separator);

            $name      = $this->getUntilToken($deps, ['T_EXTENDS' , 'T_IMPLEMENTS']);
            $a         = array_pop($name);

            array_unshift($deps, $a);

            \Sohapi\Parser\Ast::getLastInstance()->setClasse($this->concat($name))->setExtends(trim($this->concat($deps))); // TODO : Différencier le Extends et Implements

            $content    = $this->getTokensBetweenValue($tokens, '{' , '}');

            array_shift($content);

            $this->dispatch($content);
        }

        public function dispatch(&$tokens,  $handle = array(), $eldnah = null)
        {
            $before = array();

            while (($token = $this->consume($tokens)) !== null) {
                switch ($token[0]) {
                   case 'T_VARIABLE':
                        (new Variable())->visit($this, $tokens, $before, $token);
                        $before = array();
                        break;
                    case 'T_FUNCTION':
                        (new Method())->visit($this, $tokens, $before, $token);
                        $before = array();
                        break;
                    case 'T_COMMENT':
                    case 'T_DOC_COMMENT':
                        (new Comment())->visit($this, $tokens, $before, $token);
                    case 'T_STATIC':
                    case 'T_PUBLIC':
                    case 'T_PROTECTED':
                    case 'T_PRIVATE':
                    default:
                        $before[]  = $token;
                        break;
                }
            }
        }

    }
}
