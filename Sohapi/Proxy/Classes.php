<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 27/01/14
 * Time: 15:37
 */

namespace Sohapi\Proxy;


class Classes implements IProxy
{

    private $_class = array();
    private $_check = array();
    private $_register = array();

    public function setClassname(Array $classname)
    {
        $this->_class = array_merge($this->_class, $classname);
    }

    public function setCheck($check)
    {
        if (is_bool($check))
            return $this->_check = $check;

        $this->_check = array_merge($this->_check, $check);
    }


    public function process()
    {
        foreach ($this->_class as $class)
            $this->exportClass($class);
    }

    public function isRegister($classname)
    {
        return array_key_exists($classname, $this->_register);
    }

    public function getIgnoreClasses()
    {
        $valid = array();
        foreach ($this->_register as $classname => $array)
            if ($array['status'] !== 'success')
                $valid[$classname] = $array;

        return $valid;
    }

    public function getValidClasses()
    {
        $valid = array();
        foreach ($this->_register as $classname => $array)
            if ($array['status'] === 'success')
                $valid[$classname] = $array;

        return $valid;
    }

    public function getClasses()
    {
        return $this->getValidClasses();
    }

    public function getAllClasses()
    {
        return $this->_register;
    }

    public function getAllClassname()
    {
        return array_keys($this->_register);
    }

    public function getAllValidClassname()
    {

        $class = $this->getValidClasses();

        return array_keys($class);
    }


    protected function allow($classname)
    {
        $classname = $this->formatClassName($classname);

        if (is_bool($this->_check))
            return $this->_check;

        foreach ($this->_check as $check)
            if ($check instanceof \Closure) {
                if ($bool = $check($classname) === true) {
                    return true;
                }


            } else {
                if (preg_match($check, $classname) >= 1) {
                    return true;
                } // TODO make verif

            }

        return false;
    }

    protected function formatClassName($classname)
    {
        if ($classname === '' or $classname === null)
            return;


        $classname = str_replace('\\', '/', $classname);
        if ($classname[0] !== '/')
            $classname = '/' . $classname;

        return $classname;
    }

    protected function loadValidClassname($classname)
    {

        if ($classname === '')
            return;

        $classname = str_replace('/', '\\', $classname);

        if ($classname[0] !== '\\')
            return '\\' . $classname;

        return $classname;
    }

    protected function exportClass($classname = null)
    {

        if ($classname === null)
            return;

        $classname           = $this->loadValidClassname($classname);
        $classname_formatted = $this->formatClassName($classname);

        if ($this->isRegister($classname_formatted) === true)
            return;


        if ($this->allow($classname) === false) {
            $this->_register[$classname_formatted] = array(
                'status' => 'ignored',
                'class'  => $classname_formatted
            );
            return;
        }

        if (class_exists($classname) === false)
            if (interface_exists($classname) === false) {
                $this->_register[$classname_formatted] = array(
                    'status' => 'not_exists',
                    'class'  => $classname_formatted
                );
                return;
            }


        $this->_register[$classname_formatted] = $this->export($classname);
    }

    private function export($class)
    {

        $this->_register[$this->formatClassName($class)] = array();
        $reflection                                      = new \ReflectionClass($class);
        $properties                                      = array();
        $extends                                         = null;
        $implements                                      = array();
        $methods                                         = array();

        if ($reflection->getParentClass() instanceof \ReflectionClass) {

            $extends = $reflection->getParentClass()->getName();
            $this->exportClass($extends);
        }


        foreach ($reflection->getInterfaces() as $interface)
            if ($interface instanceof \ReflectionClass) {
                $implements[] = $interface->getName();

                $this->exportClass($interface->getName());
            }

        foreach ($reflection->getProperties() as $property)
            if ($property instanceof \ReflectionProperty) {

                $visibility = 'public';
                if ($property->isProtected())
                    $visibility = 'protected';
                elseif ($property->isPrivate())
                    $visibility = 'private';

                $properties[] = array(
                    'class'      => $property->class,
                    'name'       => $property->getName(),
                    'visibility' => $visibility,
                    'isStatic'   => $property->isStatic(),
                    'doc'        => $property->getDocComment()
                );
            }


        foreach ($reflection->getMethods() as $method)
            if ($method instanceof \ReflectionMethod) {

                $visibility = 'public';
                if ($method->isProtected())
                    $visibility = 'protected';
                elseif ($method->isPrivate())
                    $visibility = 'private';

                $parameter  = $this->getParameter($method);
                $annotation = $this->parseAnnotation($method->getDocComment());
                $methods[]  = array(
                    'class'      => $method->getDeclaringClass()->getName(),
                    'name'       => $method->getName(),
                    'visibility' => $visibility,
                    'isStatic'   => $method->isStatic(),
                    'isFinal'    => $method->isFinal(),
                    'isAbstract' => $method->isAbstract(),
                    'parameter'  => $parameter,
                    'annotation' => $annotation,
                    'doc'        => $method->getDocComment()
                );
            }

        return array(
            'status'      => 'success',
            'class'       => $this->formatClassName($class),
            'extends'     => $extends,
            'implements'  => $implements,
            'isInterface' => $reflection->isInterface(),
            'isTrait'     => $reflection->isTrait(),
            'isAbstract'  => $reflection->isAbstract(),
            'isInternal'  => $reflection->isInternal(),
            'doc'         => $reflection->getDocComment(),
            'properties'  => $properties,
            'methods'     => $methods,
        );
    }

    private function parseAnnotation($doc)
    {

        $line  = explode("\n", $doc);
        $annot = array();
        foreach ($line as $l) {

            $l = str_replace(array('*', '/'), '', $l);
            $l = trim($l);

            preg_match('#^@([a-z^\S]+)\s+([a-z]*)#', $l, $m);

            if (!empty($m)) {
                if ($m[1] === 'throw')
                    $this->exportClass($m[2]);


                $annot[] = array(
                    'param' => $m[1],
                    'type'  => $m[2]
                );
            }


        }

        return $annot;
    }


    private function getParameter(\ReflectionMethod $method)
    {
        $parameters = array();

        foreach ($method->getParameters() as $parameter)
            if ($parameter instanceof \ReflectionParameter) {

                $isObject = false;
                $type     = '';
                if ($parameter->isArray())
                    $type = 'Array';
                elseif ($parameter->isCallable())
                    $type = 'Closure';
                elseif ($parameter->getClass() instanceof \ReflectionClass) {
                    $type     = $this->formatClassName($parameter->getClass()->getName());
                    $isObject = true;
                    $this->exportClass($parameter->getClass()->getName());
                }

                $value = '';
                if ($parameter->isDefaultValueAvailable() === true) {

                    if ($parameter->getDefaultValue() === null)
                        $value = 'null';
                    else
                        if (is_array($parameter->getDefaultValue()))
                            $value = "'" . implode(',', $parameter->getDefaultValue()) . "'";
                        else
                            $value = "'" . $parameter->getDefaultValue() . "'";
                }

                $parameters[] = array(
                    'name'         => $parameter->getName(),
                    'type'         => $type,
                    'isObject'     => $isObject,
                    'isOptionnal'  => $parameter->isOptional(),
                    'isReference'  => $parameter->isPassedByReference(),
                    'defaultValue' => $value
                );

            }

        return $parameters;
    }

    public function getAllValidClassnameWithoutInternal()
    {
        $class  = $this->getClasses();
        $result = array();

        foreach ($class as $n => $c)
            if ($c['isInternal'] === false)
                $result[] = $n;


        return $result;
    }


}