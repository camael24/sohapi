<?php

namespace Sohtest\Asserters;

class Generic extends \atoum\asserters\variable {
    protected $_data = array();
    protected $_ns   = null;

    public function __construct(asserter\generator $generator = null, tools\variable\analyzer $analyzer = null, atoum\locale $locale = null)
    {
        parent::__construct($generator, $analyzer, $locale);
        $this->_data = \Sohapi\Parser\Model::getInstance()->getAll();
    }

    protected function call($asserter, $args)
    {
        return $this->generator->__call($asserter, array($args));
    }

    protected function checkNS($ns)
    {
        $this->call('namespace', array())->get()->contains($ns);
    }
}
