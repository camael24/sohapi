<?php
/**
 * @var Array $class
 * @var Array $folder
 * @var Array $file
 * @var String $classname
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
        </div>
        <div class="col-xs-12 col-sm-9">
            <div class="list-group">
                <?php

                if (!empty($folder)) {
                    sort($folder);
                    foreach ($folder as $element) {
                        $cf = explode('/', $element);
                        $cf = array_pop($cf);
                        echo '<a href="' . $html->resolve($element) . '" class="list-group-item"><i class="fa fa-folder-o"></i> ' . $cf . '</a>';
                    }

                }
                if (!empty($file)) {
                    sort($file);
                    foreach ($file as $element) {
                        $cf = explode('/', $element);
                        $cf = array_pop($cf);
                        echo '<a href="' . $html->resolve($element) . '" class="list-group-item"><i class="fa fa-angle-right"></i> ' . $cf . '</a>';
                    }

                }

                ?>
            </div>


        </div>
    </div>


<?php
$this->endblock();
?>