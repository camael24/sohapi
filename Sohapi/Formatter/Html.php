<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 27/01/14
 * Time: 15:38
 */

namespace Sohapi\Formatter;


use Sohapi\Greut;
use Sohapi\Proxy\IProxy;

class Html implements IExport
{
    private $_output = null;
    private $_theme = null;
    private $_argument = array();
    private $_resolve = array();
    private $_allclass = array();
    private $_stack = array();

    public function __construct($outputDirectory, Array $argument = array(), $themeDirectory = null)
    {

        if ($themeDirectory === null)
            $themeDirectory = __DIR__ . '/../Bootstrap';


        $realpath = realpath($outputDirectory);
        if ($realpath !== false)
            $this->_output = $realpath;
        else
            throw new \Exception('Can not find output path ' . $outputDirectory);

        $realpath = realpath($themeDirectory);
        if ($realpath !== false)
            $this->_theme = $realpath;
        else
            throw new \Exception('Can not find theme path ' . $themeDirectory);

        $this->_argument = array_merge($this->_argument, $argument);

        $this->install();
    }

    public function setResolve(Array $resolve)
    {
        $this->_resolve = $resolve;
    }

    protected function install()
    {
        $res = $this->_theme . '/Resource';

        if (!is_dir($res))
            throw new \Exception('Your theme are not valid');


        foreach (scandir($res) as $d)
            if ($d[0] !== '.') {
                $source = $res . '/' . $d;
                $this->copy_r($source, $this->_output . '/' . $d);
            }


    }

    protected function copy_r($path, $dest)
    {
        if (is_dir($path)) {
            @mkdir($dest);
            $objects = scandir($path);
            if (sizeof($objects) > 0) {
                foreach ($objects as $file) {
                    if ($file == "." || $file == "..")
                        continue;
                    // go on
                    if (is_dir($path . DS . $file)) {
                        $this->copy_r($path . DS . $file, $dest . DS . $file);
                    } else {
                        copy($path . DS . $file, $dest . DS . $file);
                    }
                }
            }
            return true;
        } elseif (is_file($path)) {
            return copy($path, $dest);
        } else {
            return false;
        }
    }

    public function process(IProxy $mandataire)
    {
        $this->_allclass = $mandataire->getClasses();
        /**
         * Generate Class pages
         */
        foreach ($mandataire->getValidClasses() as $name => $class)
            if ($class['isInternal'] !== true) {


                $data = new \Stdclass();

                foreach ($this->_argument as $key => $value)
                    $data->{$key} = $value;


                foreach ($class['methods'] as $i => $method)
                    $class['methods'][$i]['signature'] = $this->setSignature($method['parameter']);


                $class['class']     = substr($class['class'], 1);
                $data->classname    = $this->formatClassname($name);
                $data->classnameUrl = explode('/', $this->formatClassname($name));
                $data->class        = $class;
                $data->html         = $this;
                $greut              = new Greut($this->_theme);
                $greut->_data       = $data;
                $content            = $greut->renderFile('class.tpl.php');
                $file               = $this->_output . '/' . $this->resolve($name);
                $bytes              = file_put_contents($file, $content);
                $this->_stack[]     = array($name, $file, $bytes);
            }


        /**
         * Generate NS pages
         */

        $this->generateNS($mandataire->getAllValidClassnameWithoutInternal());

        $this->status($mandataire);

        return $this->_stack;
    }

    protected function generateNS($list)
    {
        $tree = array();
        foreach ($list as $strNamespace) {
            $current = & $tree;
            $tabns   = explode('/', $strNamespace);
            foreach ($tabns as $ns) {
                if (empty($ns)) continue;
                if (!isset($current[$ns]))
                    $current[$ns] = array();
                $current = & $current[$ns];
            }
        }

        $this->_ns($tree);
        $this->index(array_keys($tree));

    }

    protected function _ns(Array $tree, $parent = '')
    {
        foreach ($tree as $ns => $sub)
            if (count($sub) > 0) {

                $data   = new \Stdclass();
                $folder = array();
                $file   = array();

                foreach ($this->_argument as $key => $value)
                    $data->{$key} = $value;


                $ns                 = $parent . '/' . $ns;
                $data->classname    = $this->formatClassname($ns);
                $data->classnameUrl = explode('/', $this->formatClassname($ns));
                $s                  = array_keys($sub);

                foreach ($s as $class) {
                    $real = $ns . '/' . $class;
                    if (array_key_exists($real, $this->_allclass))
                        $file[] = $real;
                    else
                        $folder[] = $real;
                }


                $data->folder = $folder;
                $data->file   = $file;
                $data->html   = $this;
                $greut        = new Greut($this->_theme);
                $greut->_data = $data;
                $content      = $greut->renderFile('ns.tpl.php');
                $filename     = $this->resolve($ns);
                $file         = $this->_output . '/' . $filename;
                $bytes        = file_put_contents($file, $content);


                $classnameFormat = $this->formatClassname($ns);
                $file            = $this->_output . '/' . $classnameFormat . '.html';
                $this->_stack[]  = array($ns, $file, $bytes);

                $this->_ns($sub, $ns);
            }

    }

    protected function index($list)
    {
        $data = new \Stdclass();

        foreach ($this->_argument as $key => $value)
            $data->{$key} = $value;

        $data->classname    = $this->formatClassname('/');
        $data->classnameUrl = array();
        $data->folder       = $list;
        $data->html         = $this;
        $greut              = new Greut($this->_theme);
        $greut->_data       = $data;
        $content            = $greut->renderFile('ns.tpl.php');
        $file               = $this->_output . '/index.html';
        $bytes              = file_put_contents($file, $content);


        $this->_stack[] = array('/', $file, $bytes);
    }

    protected function status(IProxy $mandataire)
    {

        $data = new \Stdclass();

        foreach ($this->_argument as $key => $value)
            $data->{$key} = $value;


        $all     = $mandataire->getAllClasses();
        $valid   = array();
        $errored = array();

        foreach ($all as $c)
            if ($c['status'] === 'success')
                $valid[] = $c;
            else
                $errored[] = $c;


        $data->valid  = count($valid);
        $data->error  = count($errored);
        $data->all    = array_merge($valid, $errored);
        $data->html   = $this;
        $greut        = new Greut($this->_theme);
        $greut->_data = $data;
        $content      = $greut->renderFile('status.tpl.php');
        $file         = $this->_output . '/_status.html';
        $bytes        = file_put_contents($file, $content);


        $this->_stack[] = array('status', $file, $bytes);
    }

    protected function setSignature($parameters)
    {
        $stack = array();

        foreach ($parameters as $parameter)
            $stack[] = $this->formatParameter($parameter);


        return '(' . implode(',', $stack) . ')';
    }

    private function formatParameter($parameter)
    {
        $prefix = '';
        $suffix = '';
        $type   = $parameter['type'];
        $name   = '$' . $parameter['name'];
        $value  = '';

        if ($parameter['isOptionnal'] === true) {
            $prefix = '[';
            $suffix = ']';
        }

        if ($parameter['isReference'] === true)
            $name = '&' . $name;


        if ($parameter['defaultValue'] !== '')
            if ($parameter['defaultValue'] === null or $parameter['defaultValue'] === 'null')
                $value = ' = null';
            else
                $value = ' = ' . $parameter['defaultValue'];


        return $prefix . $type . ' ' . $name . $value . $suffix;
    }

    public function formatClassname($class)
    {

        $class = str_replace('\\', '/', $class);

        if (strpos($class, '/') === 0)
            $class = substr($class, 1);

        return $class;
    }


    public function resolve($class)
    {
        $class     = $this->formatClassname($class);
        $reserved  = array(
            'stdClass'
        );
        $realClass = '/' . $class;

        if (array_key_exists($realClass, $this->_allclass)) {

            $c = $this->_allclass[$realClass];
            if (array_key_exists('isInternal', $c) && $c['isInternal'] === true)
                if (array_key_exists('internal', $this->_resolve)) {
                    $route = $this->_resolve['internal'];
                    return $this->unroute($route, array('classname' => strtolower($class)));
                }
        }

        foreach ($this->_resolve as $regex => $route) {

            if ($regex !== 'internal' && preg_match($regex, $class)) {

                if (in_array($class, $reserved))
                    return 'http://www.php.net/manual/fr/reserved.classes.php';

                return $this->unroute($route, array('classname' => $class));
            }

        }


        return str_replace('/', '_', $class) . '.html';
    }

    /**
     * Thanks to Hywan and to Hoa\Router
     *
     * @param $pattern
     * @param array $variables
     * @param bool $allowEmpty
     * @return mixed
     */
    public function unroute($pattern, Array $variables,
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

}