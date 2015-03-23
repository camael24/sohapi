<?php
namespace Sohapi\Parser\Php;

class Classe extends Generic implements IParser
{
    private $_abstract = false;

    public function __construct($abstract = false)
    {
        $this->_abstract = $abstract;
    }

    public function visit($parent, &$tokens, $handle = [], $eldnah = null)
    {
        $deps      = $this->getUntilValue($tokens, '{');
        $separator = array_pop($deps);

        array_unshift($tokens, $separator);

        $name = $this->getUntilToken($deps, ['T_EXTENDS', 'T_IMPLEMENTS']);
        $a    = array_pop($name);

        if ($a[0] === 'T_STRING') {
            $name[] = $a;
        }

        $extends    = [];
        $implements = [];

        //array_unshift($deps, $a);

        if ($a[0] === 'T_EXTENDS') {
            $extends = $this->getUntilToken($deps, 'T_IMPLEMENTS');
            if ($this->tokenExists($extends, 'T_IMPLEMENTS') === true) {
                array_pop($extends);
            }
            $implements = $deps;
        }

        if ($a[0] === 'T_IMPLEMENTS') {
            $implements = $this->getUntilToken($deps, 'T_EXTENDS');
            if ($this->tokenExists($implements, 'T_EXTENDS') === true) {
                array_pop($implements);
            }
            $extends = $deps;
        }

        if ($this->_abstract === false) {
            \Sohapi\Parser\Model::getInstance()->setClasse($this->concat($name), trim($this->concat($extends)),
                trim($this->concat($implements)));
        } else {
            \Sohapi\Parser\Model::getInstance()->setAbstract($this->concat($name), trim($this->concat($extends)),
                trim($this->concat($implements)));
        }

        $content = $this->getTokensBetweenValue($tokens, '{', '}');

        array_shift($content);

        $this->dispatch($content);
    }

    public function dispatch(&$tokens, $handle = [], $eldnah = null)
    {
        $before = [];

        while (($token = $this->consume($tokens)) !== null) {
            switch ($token[0]) {
                case 'T_VARIABLE':
                    (new Variable())->visit($this, $tokens, $before, $token);
                    $before = [];
                    break;
                case 'T_FUNCTION':
                    (new Method())->visit($this, $tokens, $before, $token);
                    $before = [];
                    break;
                case 'T_COMMENT':
                case 'T_DOC_COMMENT':
                    (new Comment())->visit($this, $tokens, $before, $token);
                case 'T_STATIC':
                case 'T_PUBLIC':
                case 'T_PROTECTED':
                case 'T_PRIVATE':
                default:
                    $before[] = $token;
                    break;
            }
        }
    }

}
