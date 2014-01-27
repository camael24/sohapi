<?php
/**
 * Created by PhpStorm.
 * User: Camael24
 * Date: 27/01/14
 * Time: 10:02
 */

namespace Sohapi {

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
            $this->_check[] = $chekable;

            return $this;
        }

        public function resolution($regex, $unroute)
        {
            if (!array_key_exists($regex, $this->_resolve))
                $this->_resolve[$regex] = $unroute;

            return $this;
        }

        public function mandataire(IMandataire $object)
        {
            $object->setCheck($this->_check);
            $object->setClassname($this->_class);
            $object->setResolve($this->_resolve);

            $object->process();
            $this->_mandataire = $object;

        }

        public function export(IExport $object = null)
        {
            $object->process($this->_mandataire);
        }
    }
}