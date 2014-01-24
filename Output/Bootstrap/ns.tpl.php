<?php
/**
 * @var \Sohapi\Greut $this
 */

$this->inherits('layout.tpl.php');
$this->block('container');

$name = substr($classname, 1);
$name = explode('/', $name);
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
        </div>
        <div class="col-xs-12 col-sm-9">
            <div class="list-group">
                <?php
                /**
                 * @var Array $content
                 */
                if (!empty($content)) {
                    foreach ($content as $elment) {
                        $href = $classname . '/' . $elment;
                        $icon = '<i class="fa fa-folder-open"></i>';
                        $href = $api->resolve($href);

                        echo '<a href="' . $href . '" class="list-group-item">' . $icon . ' ' . $elment . '</a>';
                    }
                }
                ?>
            </div>


        </div>
    </div>


<?php
$this->endblock();
?>