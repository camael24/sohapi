<?php
/**
 * @var Array $class
 * @var String $classname
 * @var String $branch
 * @var String $commitUrl
 * @var String $commit
 * @var Array $classnameUrl
 * @var \Sohapi\Formatter\Html $html
 * @var \Sohapi\Greut $this
 */

$this->inherits('layout.tpl.php');
$this->block('container');

$cname = array_pop($classnameUrl);

?>


    <ol class="breadcrumb">
        <li><a href="index.html"><i class="fa fa-home"></i> </a></li>
        <?php
        $s = array();
        foreach ($classnameUrl as $n) {
            $s[] = $n;
            $i   = implode('/', $s);
            echo '<li><a href="' . $html->resolve($i) . '">' . $n . ' </a></li> ';
        }

        echo '<li class="active"> ' . $cname . '</li> ';
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
            if ($class['isAbstract'] === true)
                $type = 'Abstract';
            if ($class['isTrait'] === true)
                $type = 'Trait';
            if ($class['isInterface'] === true)
                $type = 'Interface';
            ?>

            <h2><?php echo $type . ': ' . $classname ?></h2>

            <?php
            if ($class['extends'] !== null)
                echo ' <p><a href = "' . $html->resolve($class['extends']) . '">' . $class['extends'] . ' </a></p> ';
            ?>

            <?php if (count($class['implements']) > 0) { ?>
                <div>Implements:
                    <div class="list-group">
                        <?php foreach ($class['implements'] as $implement)
                            echo ' <a href = "' . $html->resolve($implement) . '" class="list-group-item"> ' . $implement . '</a> '
                        ?>
                    </div>
                </div>
            <?php } ?>
        </div>

        <h4>Properties</h4>

        <?php foreach ($class['properties'] as $property) {
            ?>
            <div class="panel-group" id="accordion">
                <a name="p-<?php echo $property['name']; ?>"></a>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion"
                               href="#ap-<?php echo $property['name']; ?>">
                                <div class="input-group">

                                    <?php
                                    if ($property['visibility'] === 'private')
                                        echo ' <span class="input-group-addon label-danger">Private</span> ';

                                    if ($property['visibility'] === 'protected')
                                        echo ' <span class="input-group-addon label-warning">Protected</span> ';

                                    if ($property['visibility'] === 'public')
                                        echo ' <span class="input-group-addon label-success">Public</span> ';

                                    if ($property['isStatic'] === true)
                                        echo ' <span class="input-group-addon label-default">Static</span> ';

                                    ?>


                                    <div class="form-control"><?php echo $property['name']; ?></div>
                                </div>
                            </a>
                        </h4>
                    </div>
                    <div id="ap-<?php echo $property['name']; ?>" class="panel-collapse collapse">
                        <div class="panel-body">
                            <?php if ($property['doc'] !== false)
                                echo ' <pre>' . highlight_string($property['doc'], true) . ' </pre> '; ?>
                        </div>
                    </div>
                </div>
            </div>

        <?php
        }
        ?>

        <h4>Methods</h4>
        <?php foreach ($class['methods'] as $method) {
            ?>
            <div class="panel-group" id="methods">
                <a name="m-<?php echo $method['name']; ?>"></a>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#methods"
                               href="#am-<?php echo $method['name']; ?>">
                                <div class="input-group">

                                    <?php
                                    if ($method['visibility'] === 'private')
                                        echo ' <span class="input-group-addon label-danger">Private</span> ';

                                    if ($method['visibility'] === 'protected')
                                        echo ' <span class="input-group-addon label-warning">Protected</span> ';

                                    if ($method['visibility'] === 'public')
                                        echo ' <span class="input-group-addon label-success">Public</span> ';

                                    if ($method['isStatic'] === true)
                                        echo ' <span class="input-group-addon label-default">Static</span> ';

                                    if ($method['isAbstract'] === true)
                                        echo ' <span class="input-group-addon label-primary">Abstract</span> ';

                                    ?>

                                    <div
                                        class="form-control"><?php echo $method['name'] . ' ' . $method['signature'] ?></div>
                                </div>
                            </a>
                        </h4>
                    </div>
                    <div id="am-<?php echo $method['name']; ?>" class="panel-collapse collapse">
                        <div class="panel-body">
                            <table class="table table-hover">
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Required</th>
                                    <th>Default Value</th>
                                </tr>
                                <?php foreach ($method['parameter'] as $parameter) {

                                    echo ' <tr><td> ' . $parameter['name'] . ' </td><td> ';
                                    if ($parameter['isObject'] === true)
                                        echo ' <a href = "' . $html->resolve($parameter['type']) . '">' . $parameter['type'] . ' </a> ';
                                    else
                                        echo $parameter['type'];

                                    echo ' </td><td> ';

                                    if ($parameter['isOptionnal'] === false)
                                        echo ' <button class="btn btn-primary"> yes</button> ';
                                    else
                                        echo '<button class="btn btn-default"> no</button> ';

                                    echo '</td><td> ';

                                    $value = '';
                                    if ($parameter['defaultValue'] !== '')
                                        if ($parameter['defaultValue'] === null or $parameter['defaultValue'] === 'null')
                                            $value = 'null';
                                        else
                                            $value = '"' . $parameter['defaultValue'] . '"';

                                    echo $value . ' </td></tr> ';
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        <?php } ?>
    </div>
    <!--/span-->

    <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
        <?php
        if (isset($branch) && isset($commit) && isset($commitUrl)) {
            ?>
            <p class="btn-group">
                <a href="<?php echo $commitUrl; ?>" class="btn btn-success"><?php echo $branch; ?></a>
                <a href="<?php echo $commitUrl; ?>" class="btn btn-default"><?php echo $commit; ?></a>
            </p>

        <?php
        }
        ?>
        <div class="list-group">
            <a href="#" class="list-group-item active"><?php echo $classname; ?></a>
            <li class="list-group-item">Properties</li>
            <?php
            foreach ($class['properties'] as $property) {

                echo ' <a href = "#p-' . $property['name'] . '" class="list-group-item"> ';

                if ($property['visibility'] === 'private')
                    echo ' <span class="label label-danger">&nbsp;</span> ';

                if ($property['visibility'] === 'protected')
                    echo ' <span class="label label-warning">&nbsp;</span> ';

                if ($property['visibility'] === 'public')
                    echo ' <span class="label label-success">&nbsp;</span> ';


                echo '$' . $property['name'] . ' </a> ';
            }

            ?>
            <li class="list-group-item">Methods</li>
            <?php
            foreach ($class['methods'] as $method) {

                echo ' <a href = "#m-' . $method['name'] . '" class="list-group-item"> ';

                if ($method['visibility'] === 'private')
                    echo ' <span class="label label-danger">&nbsp;</span> ';

                if ($method['visibility'] === 'protected')
                    echo ' <span class="label label-warning">&nbsp;</span> ';

                if ($method['visibility'] === 'public')
                    echo ' <span class="label label-success">&nbsp;</span> ';

                echo $method['name'] . '() </a> ';
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