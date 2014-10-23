<?php
/**
 * Created by PhpStorm.
 * User: Camael24
 * Date: 24/01/14
 * Time: 09:02
 */
namespace Sohapi {
    class Greut
    {
        public $_data = null;
        private $_paths = null;
        private $_inherits = array();
        private $_blocks = array();
        private $_blocknames = array();
        private $_file = '';
        private $_headers = array();
        protected $_helpers = array();
/**
*
*/
        public function __construct($path = null)
        {
            $this->setPath($path);
            $this->setData();
        }

        public function getData()
        {
            return $this->_data;
        }

        public function setData($object = null)
        {
            if ($object === null)
                $this->_data = new \Stdclass();
            else
                $this->_data = $object;

            return $this->getData();
        }

        public function setPath($path = null)
        {
            if ($path === null)
                return;

            if ($path[strlen($path) - 1] !== '/')
                $path .= '/';

            $this->_paths = $path . '/';
        }

        public function inherits($path)
        {
            $this->_inherits[$this->_file][] = $path;
        }

        function block($blockname, $mode = 'replace')
        {
            $this->_blocknames[] = array($blockname, $mode);
            ob_start('mb_output_handler');
        }

        function endblock()
        {
            list($blockname, $mode) = array_pop($this->_blocknames);

            if (!isset($this->_blocks[$blockname]) && $mode !== false) {
                $this->_blocks[$blockname] = array('content' => ob_get_contents(), 'mode' => $mode);
            } else {
                switch ($this->_blocks[$blockname]['mode']) {
                    case 'before':
                    case 'prepend':
                        $this->_blocks[$blockname] = array(
                            'content' => $this->_blocks[$blockname]['content'] . ob_get_contents(),
                            'mode'    => $mode
                        );
                        break;
                    case 'after':
                    case 'append':
                        $this->_blocks[$blockname] = array(
                            'content' => ob_get_contents() . $this->_blocks[$blockname]['content'],
                            'mode'    => $mode
                        );
                        break;
                }
            }

            ob_end_clean();

            if ($mode === 'replace') {
                echo $this->_blocks[$blockname]['content'];
            }
        }

        protected function getFilenamePath($filename)
        {
            $path     = $this->_paths . $filename;
            $realpath = realpath($path);

            if ((false === $realpath) || !(file_exists($realpath)))
                throw new \Exception('Path ' . $path . ' (' . (($realpath === false) ? 'false' : $realpath) . ') not found!');

            return $realpath;
        }

        public function getHeaders()
        {
            return $this->_headers;
        }

        public function httpHeader($hName, $hValue, $force = true, $status = null)
        {
            $this->_headers[] = array(
                $hName,
                $hValue,
                $force,
                $status
            );
        }

        public function renderFile($filename)
        {

            $filename                   = $this->getFilenamePath($filename);
            $this->_file                = $filename;
            $this->_inherits[$filename] = array();
            // used by the placeholder

            ob_start('mb_output_handler');
            extract((array) $this->_data);
            include($filename);

            // restore args
            $content = ob_get_contents();
            ob_end_clean();

            while ($inherit = array_pop($this->_inherits[$filename]))
                $content = $this->renderFile($inherit);

            return $content;
        }
    }
}
