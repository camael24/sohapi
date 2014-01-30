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

        /**
         * Add an class to the Engine parser
         *
         * @param $classname
         * @return \Sohapi\Export
         */
        public function classname($classname)
        {
            $classname = str_replace('/', '\\', $classname);
            if ($classname[0] !== '\\')
                $classname = '\\' . $classname;

            if (!in_array($classname, $this->_class))
                $this->_class[] = $classname;

                return $this;
        }

        /**
         * Add an condition to parse the class (use for the dependancy)
         *
         * @param $chekable
         * @return $this
         */

        public function check($chekable)
        {
            if (!is_bool($this->_check))
                $this->_check[] = $chekable;

            return $this;
        }

        /**
         * By pass all verification
         *
         * @return $this
         */

        public function all()
        {

            $this->_check = true;

            return $this;
        }

        /**
         * Route an class to the local system or distant , as you want
         * http://google.fr/(?<classname>) are possible :p
         *
         * @param $regex
         * @param $unroute
         * @return $this
         */
        public function resolution($regex, $unroute)
        {
            if (!array_key_exists($regex, $this->_resolve))
                $this->_resolve[$regex] = $unroute;

            return $this;
        }

        /**
         * Use an Parser Engine
         *
         * @param IProxy $object
         * @return $this
         */

        public function proxy(IProxy $object)
        {
            $object->setCheck($this->_check);
            $object->setClassname($this->_class);
            $object->process();

            $this->_mandataire = $object;

            return $this;
        }

        /**
         * An shortcut for Export::Resolution for internal Classes
         *
         * @param string $lang
         * @param string $server
         * @return $this
         */

        public function internal($lang = 'en', $server = 'fr')
        {

            return $this->resolution('internal', 'http://' . $server . '.php.net/manual/' . $lang . '/class.(?<classname>[^\\.]).php');
        }

        /**
         * Use an Formatter Engine (by default Html or Cli)
         *
         * @param IExport $object
         * @return mixed
         */

        public function export(IExport $object = null)
        {

            $object->setResolve($this->_resolve);

            return $object->process($this->_mandataire);
        }
    }
}