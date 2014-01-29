<?php
/**
 * Created by PhpStorm.
 * User: Camael24
 * Date: 27/01/14
 * Time: 10:02
 */

namespace Sohapi {

    use Sohapi\Formatter\IExport;
    use Sohapi\Proxy\IProxy;

    class Export
    {
        private $_class = array();
        private $_check = array();
        private $_resolve = array();
        private $_mandataire = null;

        public function classname($classname)
        {
            if (!in_array($classname, $this->_class))
                $this->_class[] = $classname;

            return $this;
        }

        public function check($chekable)
        {
            if (!is_bool($this->_check))
                $this->_check[] = $chekable;

            return $this;
        }

        public function all()
        {

            $this->_check = true;

            return $this;
        }

        public function resolution($regex, $unroute)
        {
            if (!array_key_exists($regex, $this->_resolve))
                $this->_resolve[$regex] = $unroute;

            return $this;
        }

        public function proxy(IProxy $object)
        {
            $object->setCheck($this->_check);
            $object->setClassname($this->_class);
            $object->process();

            $this->_mandataire = $object;

            return $this;
        }

        public function internal($lang = 'en', $server = 'fr')
        {

            return $this->resolution('internal', 'http://' . $server . '.php.net/manual/' . $lang . '/class.(?<classname>[^\\.]).php');
        }

        public function export(IExport $object = null)
        {
            $object->setResolve($this->_resolve);

            return $object->process($this->_mandataire);
        }
    }
}