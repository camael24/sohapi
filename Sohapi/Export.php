<?php
/**
 * Created by PhpStorm.
 * User: Camael24
 * Date: 24/01/14
 * Time: 09:02
 */
namespace Sohapi {
    use Hoa\Console\Chrome\Text;
    use Hoa\File\Finder;

    class Export
    {

        private $_theme = 'Bootstrap';
        private $_argument = array();
        private $_resolution = array();
        private $_save = '';
        private $_class = array();
        private $_allowToParse = array();
        private $_disallowToParse = array();
        /**
         * @var Greut
         */
        private $_view = null;
        private $_classEverExport = array();
        private static $_collect = array();

        public function psr0($path, $topNs, $notIn = null)
        {
            if (is_dir($path) === false)
                throw new \Exception('Directory not found');

            $finder = new Finder();
            $finder->in($path)
                ->name("#.php$#")
                ->maxDepth(500);

            if ($notIn !== null)
                $finder->notIn($notIn);


            foreach ($finder as $e)
                if ($e instanceof \SplFileInfo) {
                    if (is_file($e->getPathname())) {
                        $path      = substr($e->getPathname(), strpos($e->getPathname(), $topNs));
                        $classname = substr($path, 0, strrpos($path, '.'));

                        $this->classname($classname);
                    }
                }
            return $this;
        }

        public function parse($regex)
        {
            $this->_allowToParse[] = '#' . str_replace('#', '\#', $regex) . '#';

            return $this;
        }

        public function notParse($regex)
        {
            $this->_disallowToParse[] = '#' . str_replace('#', '\#', $regex) . '#';

            return $this;
        }

        public function allow($classname)
        {
            $classname = $this->formatClassName($classname, false);
            foreach ($this->_disallowToParse as $regex) {
                if (preg_match($regex, $classname)) {

                    return false;
                }
            }


            foreach ($this->_allowToParse as $regex)
                if (preg_match($regex, $classname))
                    return true;

            return false;
        }

        public function classname($classname)
        {


            $this->_class[] = $classname;

            return $this;
        }

        public function setResolution($regex_classname, $regex_url)
        {
            $regex_classname     = '#' . str_replace('#', '\#', $regex_classname) . '#';
            $this->_resolution[] = array($regex_classname, $regex_url);
            return $this;
        }

        public function save($directory)
        {
            if ($directory === null)
                return;

            if ($directory[strlen($directory) - 1] !== '/')
                $directory .= '/';


            $this->_save = $directory;
            return $this;
        }

        public function resolve($classname)
        {

            $classname = $this->formatClassName($classname, false);
            foreach ($this->_resolution as $e) {
                $regex_class = $e[0];
                $regex_uri   = $e[1];

                if (preg_match($regex_class, $classname)) {
                    $this->_argument['classname'] = $classname;
                    return $this->_unroute($regex_uri, $this->getFormattedArgument());
                }
            }


            return '';
        }

        public function formatClassName($classname, $r = true)
        {

            $classname = str_replace('\\', '/', $classname);
            if ($r === true)
                $classname = str_replace('/', '_', $classname);

            return $classname;
        }

        protected function getFormattedArgument()
        {
            $array = array();

            foreach ($this->_argument as $key => $value)
                $array[$key] = str_replace(array('/', '\\'), '_', $value);


            return $array;
        }

        public function setTheme($theme)
        {

            $this->_theme = $theme;
            return $this;
        }

        public function getView()
        {
            return $this->_view;
        }

        public function export($dir = null)
        {

            $this->save($dir);


            $this->_theme = realpath(__DIR__ . '/../Output/' . $this->_theme);

            self::$_collect = array(array('Class', 'File', 'Bytes'));
            if ($this->_theme === false)
                throw new \Exception('We cant find the directory ' . __DIR__ . '/../Output/' . $this->_theme . ' please check manually');

            foreach ($this->_class as $classname)
                $this->exportClass($classname);


            $this->generateNamespacing();

            echo Text::columnize(self::$_collect);
        }

        public function exportClass($classname)
        {

            if (in_array($classname, $this->_classEverExport))
                return;

            if ($this->allow($classname) === false)
                return;

            $view                     = new Greut($this->_theme);
            $this->_classEverExport[] = $classname;
            $data                     = new \Stdclass();
            $data->classname          = $classname;
            $classnameFormat          = $this->formatClassName($classname);
            $data->classnameFormat    = $classnameFormat;
            $data->reflection         = new \ReflectionClass($classname);
            $data->api                = $this;
            $view->_data              = $data;
            $content                  = $view->renderFile('class.tpl.php');
            $file                     = $this->_save . $classnameFormat . '.html';
            $bytes                    = file_put_contents($file, $content);
            self::$_collect[]         = array($classname, $classnameFormat . '.html', $bytes);

        }

        protected function generateNamespacing()
        {
            $stack = array();
            foreach (self::$_collect as $e)
                if ($e[0] !== 'Class') {
                    $stack[] = $e[0];

                }


            $tree = array();
            foreach ($stack as $strNamespace) {
                $current = & $tree;
                $tabns   = explode('\\', $strNamespace);
                foreach ($tabns as $ns) {
                    if (empty($ns)) continue;
                    if (!isset($current[$ns]))
                        $current[$ns] = array();
                    $current = & $current[$ns];
                }
            }

            $this->_ns($tree);


            $view             = new Greut($this->_theme);
            $data             = new \Stdclass();
            $data->classname  = '';
            $data->api        = $this;
            $data->content    = array_keys($tree);
            $view->_data      = $data;
            $content          = $view->renderFile('ns.tpl.php');
            $file             = $this->_save . 'index.html';
            $bytes            = file_put_contents($file, $content);
            self::$_collect[] = array('/', 'index.html', $bytes);

        }

        protected function _ns(Array $tree, $parent = '')
        {
            foreach ($tree as $ns => $sub) {
                if (count($sub) > 0) {
                    $view             = new Greut($this->_theme);
                    $data             = new \Stdclass();
                    $data->current    = $ns;
                    $ns               = $parent . '/' . $ns;
                    $data->classname  = $ns;
                    $data->api        = $this;
                    $data->content    = array_keys($sub);
                    $view->_data      = $data;
                    $classnameFormat  = $this->formatClassName($ns);
                    $content          = $view->renderFile('ns.tpl.php');
                    $file             = $this->_save . $classnameFormat . '.html';
                    $bytes            = file_put_contents($file, $content);
                    self::$_collect[] = array($ns, $classnameFormat . '.html', $bytes);

                    $this->_ns($sub, $ns);
                }
            }


        }


        protected function _unroute($pattern, Array $variables,
                                    $allowEmpty = true)
        {

            // (?<named>…)
            $out = preg_replace_callback(
                '#\(\?\<([^>]+)>[^\)]*\)[\?\*\+]{0,2}#',
                function (Array $matches) use (&$variables, &$allowEmpty) {

                    $m = strtolower($matches[1]);

                    if (!isset($variables[$m]) || '' === $variables[$m])
                        if (true === $allowEmpty)
                            return '';
                        else
                            throw new \Exception(
                                'Variable ' . $m . ' is empty and it is not allowed when unrouting rule.');

                    return $variables[$m];
                },
                // (-…)
                preg_replace('#\(\?\-?[imsxUXJ]+\)#', '', $pattern)
            );

            // (?:
            $out = preg_replace('#(?<!\\\)\(\?:#', '(', $out);

            // (…)?, (…)*
            $out = preg_replace('#(?<!\\\)\((.*)(?<!\\\)\)[\?\*]#', '\1', $out);

            // (…)+
            $out = preg_replace('#(?<!\\\)\((.+)(?<!\\\)\)\+#', '\1', $out);

            // …?, …*, …+
            $out = preg_replace('#(.)(?<![\)\\\])[\?\*\+]#', '\1', $out);

            return str_replace(
                array(
                    '\.', '\\\\', '\+', '\*', '\?', '\[', '\]', '\^', '\$', '\(',
                    '\)', '\{', '\}', '\=', '\!', '\<', '\>', '\|', '\:', '\-'
                ),
                array(
                    '.', '\\', '+', '*', '?', '[', ']', '^', '$', '(',
                    ')', '{', '}', '=', '!', '<', '>', '|', ':', '-'
                ),
                $out
            );
        }

        public function getClassParses()
        {
            return $this->_class;
        }


    }
}
 