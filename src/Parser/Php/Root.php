<?php
namespace Sohapi\Parser\Php;

class Root extends Generic implements IParser
{
    public function visit($parent, &$tokens, $handle = [], $eldnah = null)
    {

        $a       = $tokens;
        $aliased = [];

        while ($this->valueExists($a, 'class_alias')) {

            $this->getUntilValue($a, 'class_alias');
            $b     = $this->getUntilValue($a, ';');
            $alias = $this->getTokensBetweenValue($b, '(', ')');
            $class = $this->getUntilValue($alias, ',');
            $class = substr($this->getToken($class, 'T_CONSTANT_ENCAPSED_STRING'), 1, -1);
            $alias = substr($this->getToken($alias, 'T_CONSTANT_ENCAPSED_STRING'), 1, -1);

            \Sohapi\Parser\Model::getInstance()->setAlias($alias, $class);
        }

        unset($a, $b, $alias, $class);

        $this->dispatch($tokens, $handle, $eldnah);
    }

    public function dispatch(&$tokens, $handle = [], $eldnah = null)
    {
        $before = [];
        // TODO : Set USE;
        while (($token = $this->consume($tokens)) !== null) {
            switch ($token[0]) {
                case 'T_COMMENT':
                case 'T_DOC_COMMENT':
                    (new Comment())->visit($this, $tokens, $before, $token);
                case 'T_NAMESPACE':
                    (new Ns())->visit($this, $tokens, $before, $eldnah);
                    $before = [];
                    break;
                case 'T_INTERFACE':
                    (new Iface())->visit($this, $tokens, $before, $eldnah);
                    $before = [];
                    break;
                case 'T_ABSTRACT':
                    (new Abs())->visit($this, $tokens, $before, $eldnah);
                    $before = [];
                    break;
                case 'T_CLASS': // TODO : Interface Traits Abstract
                    (new Classe())->visit($this, $tokens, $before, $eldnah);
                    $before = [];
                    break;
                case 'T_USE':
                    (new Alias())->visit($this, $tokens, $before, $eldnah);
                    $before = [];
                    break;
                default:
                    $before[] = $token;
                    break;
            }
        }
    }

}
