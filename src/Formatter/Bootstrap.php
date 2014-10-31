<?php
namespace Sohapi\Formatter {
    class Bootstrap extends Formatter
    {
        protected $_resource = '';
        protected $_allclass = array();
        public function render()
        {
            $this->_resource = realpath(__DIR__.'/../Template/Bootstrap/Resource/');

            if ($this->copyDirectory($this->_resource, $this->getArgument('output')) === false) {
                throw new \Exception("Error durring copy resource theme", 1);
            }

            $fqcn = array();

            foreach ($this->_namespace as $namespace) {

                 if (isset($this->_classe[$namespace])) {
                    foreach ($this->_classe[$namespace] as $classe) {
                        $f      = $namespace.'\\'.$classe['class'];
                        if (!in_array($f, $fqcn)) {
                            $fqcn[] = $f;
                            $this->generateClass($namespace, $classe , 'class');
                        }
                    }
                }

                if (isset($this->_interface[$namespace])) {
                    foreach ($this->_interface[$namespace] as $interface) {
                        $f      = $namespace.'\\'.$interface['interface'];
                        if (!in_array($f, $fqcn)) {
                            $fqcn[] = $f;
                            $this->generateClass($namespace, $interface, 'interface');
                        }
                    }
                }

                if (isset($this->_abstract[$namespace])) {
                    foreach ($this->_abstract[$namespace] as $abstract) {
                        $f      = $namespace.'\\'.$abstract['abstract'];
                        if (!in_array($f, $fqcn)) {
                            $fqcn[] = $f;
                            $this->generateClass($namespace, $abstract, 'abstract');
                        }
                    }
                }
            }

            $this->_allclass = array_merge($this->_allclass, $fqcn);

            $this->generateNs($fqcn);
        }

        protected function generateClass($ns, $element, $type)
        {
            switch ($type) {
                case 'class':
                    $classname  = $element['class'];
                    break;
                case 'interface':
                    $classname  = $element['interface'];
                    break;
                case 'abstract':
                    $classname  = $element['abstract'];
                    break;
            }

            $greut      = new \Sohoa\Framework\View\Greut();
            if(isset($element['extends']))
                $extends    = explode(',' ,$element['extends']);
            else
                $extends = array();

            if(isset($element['implements']))
                $implements = explode(',' ,$element['implements']);
            else
                $implements = array();

            foreach ($extends as $key => $value) {
                $extends[$key] = $this->resolveClass($value, $ns);
            }
            foreach ($implements as $key => $value) {
                $implements[$key] = $this->resolveClass($value, $ns);
            }

            if(isset($ns[0]) and $ns[0] !== '\\')
                $ns = '\\'.$ns;

            $fqcn               = $ns.'\\'.$classname;
            $data               = $greut->getData();
            $data->namespace    = $ns;
            $data->classname    = $classname;
            $data->classcomm    = $element['comment'];
            $data->extends      = $extends;
            $data->implements   = $implements;
            $data->fqcn         = $fqcn;
            $data->type         = $type;

            if(isset($this->_properties[$ns][$classname]))
                $data->properties = $this->_properties[$ns][$classname];

            if(isset($this->_methods[$ns][$classname]))
                $data->methods = $this->_methods[$ns][$classname];

            $greut->setPath($this->_resource.'/../');
            $greut->setData($data);

            $file = $greut->renderFile('Class.tpl.php')."\n";
            $uri  = $this->_uri($fqcn);

            if($this->getArgument('dry') === false)
                file_put_contents($uri, $file);

            if($this->getArgument('debug') === true)
                echo 'CLASS : '.$uri."\n";
        }

        public function generateNs($list)
        {
            $tree = array();
            foreach ($list as $strNamespace) {
                $current = & $tree;
                $tabns = explode('\\', $strNamespace);
                foreach ($tabns as $ns) {
                    if (empty($ns))
                        continue;
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
                    $classname      = $ns;
                    $ns             = $parent . '\\' . $ns;
                    $greut          = new \Sohoa\Framework\View\Greut();
                    $data           = $greut->getData();
                    $data->fqcn     = $ns;
                    $s              = array_keys($sub);
                    $folder         = array();
                    $file           = array();

                    foreach ($s as $class) {
                        $real = $ns . '\\' . $class;
                        if (in_array(substr($real, 1), $this->_allclass))
                            $file[] = $real;
                        else
                            $folder[] = $real;
                    }

                    $data->file     = $file;
                    $data->folder   = $folder;
                    $data->all      = $this->_allclass;

                    $greut->setPath($this->_resource.'/../');

                    $file = $greut->renderFile('Ns.tpl.php')."\n";
                    $uri  = $this->_uri($ns);

                    if($this->getArgument('dry') === false)
                        file_put_contents($uri, $file);

                    if($this->getArgument('debug') === true)
                        echo 'NS : '.$uri."\n";

                    $this->_ns($sub, $ns);
                }

        }

        protected function index($list)
        {

            $classname      = '';
            $ns             = '';
            $greut          = new \Sohoa\Framework\View\Greut();
            $data           = $greut->getData();
            $data->fqcn     = $ns;
            $folder         = array();
            $file           = array();

            foreach ($list as $class) {
                $real = $ns . '\\' . $class;
                if (in_array(substr($real, 1), $this->_allclass))
                    $file[] = $real;
                else
                    $folder[] = $real;
            }

            $data->file     = $file;
            $data->folder   = $folder;
            $data->all      = $this->_allclass;

            $greut->setPath($this->_resource.'/../');

            $file = $greut->renderFile('Ns.tpl.php')."\n";
            $uri  = $this->_uri('index');

            if($this->getArgument('dry') === false)
                file_put_contents($uri, $file);
            if($this->getArgument('debug') === true)
                echo 'NS : '.$uri."\n";

        }

        protected function _uri($classname)
        {
            return $this->getArgument('output').'\\'.str_replace(['/', '\\'], '_', $classname).'.html';
        }

    }
}
