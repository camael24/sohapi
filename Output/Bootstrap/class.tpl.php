<?php
/**
 * @var \Sohapi\Greut $this
 */

$this->inherits('layout.tpl.php');
$this->block('container');

/**
 * @var \ReflectionClass $reflection
 * @var \Sohapi\Export $api
 */


$name = $reflection->getName();
$name = $api->formatClassName($name);
$name = explode('_', $name);
$cname = array_pop($name);
?>


    <ol class="breadcrumb">
        <li><a href="index.html"><i class="fa fa-home"></i> </a></li>
        <?php
        $s = array();
        foreach ($name as $n) {
            $s[] = $n;
            echo '<li><a href="' . $api->formatClassName('/' . implode('/', $s)) . '.html">' . $n . '</a></li>';
        }

        echo '<li class="active">' . $cname . '</li>';
        ?>
    </ol>

    <div class="row row-offcanvas row-offcanvas-right">

    <div class="col-xs-12 col-sm-9">
    <p class="pull-right visible-xs">
        <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
    </p>
    <div class="jumbotron">
        <?php
        $type = 'Class';
        if ($reflection->isInterface())
            $type = 'Interface';
        if ($reflection->isTrait())
            $type = 'Trait';
        if ($reflection->isAbstract())
            $type = 'Abstract class'
        ?>
        <h2><?php echo $type . ' : ' . $classname; ?></h2>
        <?php
        $extends = $reflection->getParentClass();
        if ($extends instanceof \ReflectionClass) {
            echo '<p>Extends :';
            $parentName = '\\' . $extends->getName();
            $api->exportClass($parentName);
            $uri = $api->resolve($parentName);

            echo '<a href="' . $uri . '">' . $extends->name . '</a></p>';
        }
        $implements = $reflection->getInterfaces();
        if (count($implements) > 0) {
            echo '<div>Implements: <div class="list-group">';
            foreach ($implements as $implement)
                if ($implement instanceof \ReflectionClass) {

                    $parentName = '\\' . $implement->getName();
                    $api->exportClass($parentName);
                    $uri = $api->resolve($parentName);

                    echo '<a href="' . $uri . '" class="list-group-item">' . $implement->name . '</a>';
                }

            echo '</div></div>';
        }

        ?>
    </div>

    <h4>Properies</h4>

    <div class="panel-group" id="accordion">
        <?php
        foreach ($reflection->getProperties() as $property)
            if ($property instanceof \ReflectionProperty) {
                $visibilty = 'default';
                $color     = 'info';
                if ($property->isPrivate()) {
                    $visibilty = 'private';
                    $color     = 'danger';
                }
                if ($property->isProtected()) {
                    $visibilty = 'protected';
                    $color     = 'warning';
                }
                if ($property->isPublic()) {
                    $visibilty = 'public';
                    $color     = 'success';
                }


                $out = '<a name="ap-' . $property->getName() . '"></a>
                    <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#p-' . $property->getName() . '">
                           <div class="input-group">';

                $out .= '<span class="input-group-addon label-' . $color . '" >' . $visibilty . '</span>';


                if ($property->isStatic())
                    $out .= '<span class="input-group-addon label-default" >Static</span>';


                $out .= '<div class="form-control">' . $property->getName() . '</div></div>
                            </a>
                        </h4>
                    </div>
                    <div id="p-' . $property->getName() . '" class="panel-collapse collapse">
                        <div class="panel-body">' . highlight_string($property->getDocComment(), true) . '
                        </div>
                    </div>
                </div>';

                echo $out;
            }
        ?>
    </div>


    <h4>Methods</h4>

    <div class="panel-group" id="methods">
        <?php
        foreach ($reflection->getMethods() as $method)
            if ($method instanceof \ReflectionMethod) {
                $visibilty = 'default';
                $color     = 'info';
                if ($method->isPrivate()) {
                    $visibilty = 'private';
                    $color     = 'danger';
                }
                if ($method->isProtected()) {
                    $visibilty = 'protected';
                    $color     = 'warning';
                }
                if ($method->isPublic()) {
                    $visibilty = 'public';
                    $color     = 'success';
                }


                $out = '<a name="am-' . $method->getName() . '"></a>
                    <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#methods" href="#m-' . $method->getName() . '">
                           <div class="input-group">';

                $out .= '<span class="input-group-addon label-' . $color . '" >' . $visibilty . '</span>';

                if ($method->getDeclaringClass()->getName() !== substr($classname, 1))
                    $out .= '<span class="input-group-addon label-info" >Inherit</span>';

                if ($method->isFinal())
                    $out .= '<span class="input-group-addon label-warning" >Final</span>';

                if ($method->isStatic())
                    $out .= '<span class="input-group-addon label-default" >Static</span>';


                $param = function (Array $parameters) use (&$api) {
                    if (count($parameters) === 0)
                        return ' ( <i>void</i> ) ';

                    $out = array();

                    foreach ($parameters as $parameter)
                        if ($parameter instanceof \ReflectionParameter) {
                            $prefix = '';
                            $suffix = '';
                            $type   = '';
                            $ref    = '';
                            $value  = '';

                            if ($parameter->isArray())
                                $type = 'Array ';
                            elseif ($parameter->isCallable())
                                $type = 'Closure ';

                            elseif ($parameter->getClass() instanceof \ReflectionClass) {
                                $type = '\\' . $parameter->getClass()->name;

                            }


                            if ($parameter->isPassedByReference())
                                $ref = '&';


                            if ($parameter->isOptional()) {
                                $prefix = '<i>[';
                                $value  = '';
                                $suffix = ']</i>';

                                if ($parameter->isDefaultValueAvailable() === true)
                                    if ($parameter->getDefaultValue() === null)
                                        $value = 'null';
                                    else
                                        if (is_array($parameter->getDefaultValue()))
                                            $value = "'" . implode(',', $parameter->getDefaultValue()) . "'";
                                        else
                                            $value = "'" . $parameter->getDefaultValue() . "'";

                                $value = ' = ' . $value;
                            }

                            $out[] = $prefix . $type . ' ' . $ref . '$' . $parameter->getName() . $value . $suffix;
                        }

                    return ' ( ' . implode(' , ', $out) . ' )';

                };

                $parameters = $method->getParameters();
                $comment    = $method->getDocComment();
                $out .= '<div class="form-control">' . $method->getName() . $param($parameters) . '</div></div>
                            </a>
                        </h4>
                    </div>
                    <div id="m-' . $method->getName() . '" class="panel-collapse collapse ' . (($comment === false) ? '' : 'in') . '">
                        <div class="panel-body">';
                if (!empty($parameters)) {
                    $out .= '<table class="table table-hover"><tr><th>Name</th><th>Type</th><th>Required</th><th>Default Value</th></tr>';
                    foreach ($parameters as $parameter)
                        if ($parameter instanceof \ReflectionParameter) {

                            if ($parameter->isArray())
                                $type = 'Array ';
                            elseif ($parameter->isCallable())
                                $type = 'Closure ';

                            elseif ($parameter->getClass() instanceof \ReflectionClass) {
                                $type    = '\\' . $parameter->getClass()->name;
                                $resolve = $api->resolve($type);
                                $api->exportClass($type);
                                if ($resolve !== '')
                                    $type = '<a href="' . $resolve . '">' . $type . '</a>';
                            }

                            $required = '<button class="btn btn-primary">yes</button>';
                            if ($parameter->isOptional() === true)
                                $required = '<button class="btn btn-default">no</button>';

                            $value = '';
                            if ($parameter->isDefaultValueAvailable() === true)
                                if ($parameter->getDefaultValue() === null)
                                    $value = 'null';
                                else
                                    if (is_array($parameter->getDefaultValue()))
                                        $value = "'" . implode(',', $parameter->getDefaultValue()) . "'";
                                    else
                                        $value = "'" . $parameter->getDefaultValue() . "'";


                            $out .= '<tr><td>$' . $parameter->getName() . '</td><td>' . $type . '</td><td>' . $required . '</td><td>' . $value . '</td></tr>';

                        }

                    $out .= '</table>';
                }


                $out .= '<pre>' . highlight_string($comment, true) . '</pre></div></div></div>';

                echo $out;


            }
        ?>
    </div>


    </div>
    <!--/span-->
    <?php
    $formatItem = function ($href, $visibilty, $label, $isStatic, $isFinal = false) {

        switch ($visibilty) {
            case 'private':
                $visibilty = 'danger';
                break;
            case 'protected':
                $visibilty = 'warning';
                break;
            case 'public':
                $visibilty = 'success';
                break;
            default:
                $visibilty = 'info';
        }

        return '<a href="' . $href . '-' . $label . '" class="list-group-item"><span class="label label-' . $visibilty . '">&nbsp;</span> ' . $label . '</a>';
    }
    ?>
    <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
        <div class="list-group">
            <a href="#" class="list-group-item active"><?php echo $classname; ?></a>
            <li class="list-group-item">Properties</li>
            <?php
            foreach ($reflection->getProperties() as $property)
                if ($property instanceof \ReflectionProperty) {
                    $visibilty = 'default';
                    if ($property->isPrivate())
                        $visibilty = 'private';
                    if ($property->isProtected())
                        $visibilty = 'protected';
                    if ($property->isPublic())
                        $visibilty = 'public';

                    echo $formatItem('#ap', $visibilty, $property->getName(), $property->isStatic());
                }

            echo '<li class="list-group-item">Methods</li>';
            foreach ($reflection->getMethods() as $method)
                if ($method instanceof \ReflectionMethod) {
                    $visibilty = 'default';
                    if ($method->isPrivate())
                        $visibilty = 'private';
                    if ($method->isProtected())
                        $visibilty = 'protected';
                    if ($method->isPublic())
                        $visibilty = 'public';

                    echo $formatItem('#am', $visibilty, $method->name, $method->isStatic(), $method->isFinal());


                }
            ?>

        </div>
    </div>
    <!--/span-->
    </div>
    <!--/row-->

<?php
$this->endblock();
?>