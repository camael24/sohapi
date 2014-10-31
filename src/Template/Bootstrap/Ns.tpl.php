<?php
$this->inherits('Base.tpl.php');
$this->block('content');
$uri = function ($c) {
    return str_replace(['/', '\\'], '_', $c).'.html';
};

 $tconcat = function ($tokens) {
    if(is_array($tokens) === false)

        return $tokens;

    $buffer = '';
    foreach ($tokens as $key => $value) {
        $buffer .= strval($value[1]);
    }

    return trim($buffer);
};
?>
    <ol class="breadcrumb">
        <?php
        $f = explode('\\', $fqcn);
        $o = array();
        foreach ($f as $i => $e) {
            $o[] = $e;
            if($i === 0)
                echo '<li><a href="index.html"><i class="fa fa-home"></i> </a></li>';
            else if((count($f) - 1)=== $i)
                echo '<li class="active">'.$e.'</li>';
            else
                echo '<li'.(((count($f) - 1)=== $i) ? ' class="active"' : '').'><a href="'.$uri(implode('/', $o)).'">'.$e.'</a></li>';
        }
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
                        echo '<a href="'.$uri($cf).'" class="list-group-item"><i class="fa fa-folder-o"></i> ' . $cf . '</a>';
                    }
                }

                if (!empty($file)) {
                    sort($file);
                    foreach ($file as $element) {
                        $cf = explode('/', $element);
                        $cf = array_pop($cf);
                        echo '<a href="'.$uri($cf).'" class="list-group-item"><i class="fa fa-angle-right"></i> ' . $cf . '</a>';
                    }
                }
                ?>
            </div>


        </div>
    </div>


<?php
$this->endblock();
?>