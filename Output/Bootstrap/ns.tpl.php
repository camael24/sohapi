<?php
/**
 * @var Array $class
 * @var Array $all
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

                if (!empty($content)) {
                    sort($content);
                    foreach ($content as $elment) {
                        $href = $classname . '/' . $elment;
                        $real = '/' . $href;

                        if (array_key_exists($real, $all))
                            echo '<a href="' . $html->resolve($href) . '" class="list-group-item"><i class="fa fa-angle-right"></i> ' . $elment . '</a>';
                        else
                            echo '<a href="' . $html->resolve($href) . '" class="list-group-item"><i class="fa fa-folder-o"></i> ' . $elment . '</a>';
                    }
                }
                ?>
            </div>


        </div>
    </div>


<?php
$this->endblock();
?>