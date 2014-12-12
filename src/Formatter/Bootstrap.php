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
            $c    = array();

            // TODO : Parse phpDoc
            // TODO : Arguments, Throw Return cliquable
            // TODO : Moteur de recherche
            // TODO : Raccourci clavier
            // TODO : Vue UML (Graphviz)
            // TODO : Rebondir sur les découvertes de dépendance (via option)
            // TODO : Fallback sur les classes (php.net etc ...)
            // TODO : TU
            // TODO : Parse option : SourceFile, hash, branch, remote, etc ...
            // TODO : Utilisation des Traits
            // TODO : Aliasing (class_alias, FlexEntity)
            // TODO : Functionnal (With and Without Namespace)
            // TODO : Bug when class Foo{}
            // TODO : abstract & implements

            foreach ($this->_namespace as $namespace) {
                 if (isset($this->_classe[$namespace])) {
                    foreach ($this->_classe[$namespace] as $classe) {
                        $f      = $namespace.'\\'.$classe['class'];
                        if (!in_array($f, $fqcn)) {
                            $fqcn[] = $f;
                            $c[] = [$namespace, $classe , 'class'];
                        }
                    }
                }

                if (isset($this->_interface[$namespace])) {
                    foreach ($this->_interface[$namespace] as $interface) {
                        $f      = $namespace.'\\'.$interface['interface'];
                        if (!in_array($f, $fqcn)) {
                            $fqcn[] = $f;
                            $c[] = [$namespace, $interface, 'interface'];
                        }
                    }
                }

                if (isset($this->_abstract[$namespace])) {
                    foreach ($this->_abstract[$namespace] as $abstract) {
                        $f      = $namespace.'\\'.$abstract['abstract'];
                        if (!in_array($f, $fqcn)) {
                            $fqcn[] = $f;
                            $c[] = [$namespace, $abstract, 'abstract'];
                        }
                    }
                }
            }

            $this->_allclass = array_merge($this->_allclass, $fqcn);

            foreach ($c as $value) {
                $this->generateClass($value[0], $value[1], $value[2]);
            }

            $this->generateNs($fqcn);
            $this->generateSidebar();
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

            $data->options      = $this->getArgument('options');
            $data->namespace    = $ns;
            $data->title        = substr($ns, 1) .'\\'. $classname;
            $data->classname    = $classname;
            $data->allclass     = $this->_allclass;
            $data->classcomm    = $this->extractFromComment($element['comment']);
            $data->extends      = $this->clean($extends);
            $data->implements   = $this->clean($implements);
            $data->fqcn         = $fqcn;
            $data->type         = $type;

            if(isset($this->_properties[substr($ns,1)][$classname]))
                $data->properties = $this->_properties[substr($ns,1)][$classname];
            else
                if($this->getArgument('debug') === true)
                    echo 'NO PROPERTIES on '.$ns.'\\'.$classname."\n";

            if(isset($this->_methods[substr($ns,1)][$classname]))
                $data->methods = $this->_methods[substr($ns,1)][$classname];
            else
                if($this->getArgument('debug') === true)
                    echo 'NO METHODS on '.$ns.'\\'.$classname."\n";


            $greut->setPath($this->_resource.'/../');
            $greut->setData($data);

            $file = $greut->renderFile('Class.tpl.php')."\n";
            $uri  = $this->_uri($fqcn);

            if($this->getArgument('dry') === false)
                file_put_contents($uri, $file);

            echo 'FILE : '.$uri."\n";
        }
        protected function generateSidebar()
        {

            $greut              = new \Sohoa\Framework\View\Greut();
            $data               = $greut->getData();
            $data->allclass     = $this->_allclass;

            $greut->setPath($this->_resource.'/../');
            $greut->setData($data);

            $file = $greut->renderFile('Appjs.tpl.php')."\n";
            $uri  =  $this->getArgument('output').'/js/app-treeview.js';

            file_put_contents($uri, $file);

            echo 'JS : '.$uri."\n";
        }

        public function generateNs($list)
        {
            $tree = $this->_tree($list);
            $this->_ns($tree);
            $this->index(array_keys($tree));
        }

        protected function _tree($list) {
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

            return $tree;
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

                    $data->title        = substr($ns,1);
                    $data->file         = $file;
                    $data->folder       = $folder;
                    $data->all          = $this->_allclass;

                    $greut->setPath($this->_resource.'/../');

                    $file = $greut->renderFile('Ns.tpl.php')."\n";
                    $uri  = $this->_uri($ns);

                    if($this->getArgument('dry') === false)
                        file_put_contents($uri, $file);

                    //if($this->getArgument('debug') === true)
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

            $data->file         = $file;
            $data->title        = '';
            $data->folder       = $folder;
            $data->all          = $this->_allclass;

            $greut->setPath($this->_resource.'/../');

            $file = $greut->renderFile('Ns.tpl.php')."\n";
            $uri  = $this->_uri('index');

            if($this->getArgument('dry') === false)
                file_put_contents($uri, $file);

            //if($this->getArgument('debug') === true)
                echo 'NS : '.$uri."\n";

        }

        protected function clean($array) {
            $a = array();
            foreach ($array as $key => $value) {
                $value = trim($value);
                if(isset($value) and $value !== '' and $value !== '\\')
                    $a[] = $value;
            }

            return $a;
        }

        protected function _uri($classname)
        {
            if($classname[0] === '/' or $classname[0] === '\\')
                $classname = substr($classname, 1);
            return $this->getArgument('output').'\\'.urlencode(utf8_decode(str_replace(['/', '\\'], '_', $classname))).'.html';
        }

        public static function extractFromComment($string)
        {
            return $string;
        }

    }
}
