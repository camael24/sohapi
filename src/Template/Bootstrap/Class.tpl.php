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
        <div class="jumbotron">
            <h2><?php echo ucfirst($type) . ': ' . $classname ?></h2>
            <?php if(!empty($extends)) { ?>
            <div>Extends:
                <div class="list-group">
                    <?php foreach($extends as $c) ?>
                    <a href = "<?php echo $uri($c); ?>" class="list-group-item"><?php echo $c; ?></a>
                </div>
            </div>
            <?php } ?>
            <?php if(!empty($implements)) { ?>
            <div>Implements:
                <div class="list-group">
                    <?php foreach ($implements as $iface) { ?>
                        <a href = "<?php echo $uri($iface); ?>" class="list-group-item"><?php echo $iface; ?></a>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
            <?php
                if(isset($classcomm)){
                    echo '<pre>'.$classcomm.'</pre>';
                }
            ?>
        </div>

        <h4>Properties</h4>
        <?php if (isset($properties)) { ?>
                <?php foreach ($properties as $property) {?>
                <div class="panel-group" id="accordion">
                <a name="p-<?php echo substr(strtolower($property['name']), 1); ?>"></a>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#ap-<?php echo substr(strtolower($property['name']), 1); ?>">
                                <div class="input-group">
                                    <?php if(isset($property['visibility'])) ?>
                                        <?php switch ($property['visibility']) {
                                            case 'private':
                                                echo '<span class="input-group-addon label-danger">Private</span>';
                                            break;
                                            case 'protected':
                                                echo '<span class="input-group-addon label-warning">Protected</span>';
                                            break;
                                            case 'public':
                                            default:
                                                echo '<span class="input-group-addon label-success">Public</span>';
                                            break;
                                     }
                                    ?>
                                    <div class="form-control"><?php echo $property['name']; ?></div>
                                </div>
                            </a>
                        </h4>
                    </div>
                    <div id="ap-<?php echo substr(strtolower($property['name']), 1); ?>" class="panel-collapse collapse">
                        <div class="panel-body"><pre><?php if(isset($property['comment']))  echo $property['comment']; ?></pre></div>
                    </div>
                </div>
            </div>
         <?php
                }
            } ?>

        <h4>Methods</h4>
        <?php if (isset($methods)) { ?>
                <?php foreach ($methods as $method) {?>

                <div class="panel-group">
                <a name="m-<?php echo strtolower($method['name']); ?>"></a>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#methods"
                               href="#am-<?php echo strtolower($method['name']); ?>">
                                <div class="input-group">

                                    <?php if(isset($method['visibility'])) ?>
                                        <?php switch ($method['visibility']) {
                                            case 'private':
                                                echo '<span class="input-group-addon label-danger">Private</span>';
                                            break;
                                            case 'protected':
                                                echo '<span class="input-group-addon label-warning">Protected</span>';
                                            break;
                                            case 'public':
                                            default:
                                                echo '<span class="input-group-addon label-success">Public</span>';
                                            break;
                                        }
                                        if(isset($method['static']) && $method['static'] === true) ?>
                                        <span class="input-group-addon label-primary">Static</span>
                                    <div class="form-control"><?php echo $method['name']?> (<?php echo $tconcat($method['arguments']); ?>)</div>
                                </div>
                            </a>
                        </h4>
                    </div>
                    <div id="am-<?php echo strtolower($method['name']); ?>" class="panel-collapse collapse">
                    <div class="panel-body"><pre><?php if(isset($method['comment'])) echo $method['comment']; ?></pre></div>
                </div>
            </div>
        </div>
        <?php
            }
        } ?>
    </div>
    <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">

        <!--p>
        <div class="btn-group">
            <a href="https://github.com/camael24/sohapi/tree/e823fe2c2da2904cd6661655e7ae7add127bccf4/Sohapi/Formatter/Html.php" class="btn btn-success"><i class="fa fa-github"></i></a>
            <button class="btn btn-default">master  e823fe2</button>
        </div>
        </p-->

        <div class="list-group">
        <a href="#" class="list-group-item active"><?php echo $fqcn; ?></a>
        <li class="list-group-item">Properties</li>
            <?php if (isset($properties)) { ?>
                <?php foreach ($properties as $property) {?>
                    <a href = "#p-<?php echo substr(strtolower($property['name']), 1); ?>" class="list-group-item">
                    <?php if(isset($property['visibility']))
                            switch ($property['visibility']) {
                                case 'private':
                                    echo '<span class="label label-danger">&nbsp;</span>';
                                break;
                                case 'protected':
                                    echo '<span class="label label-warning">&nbsp;</span>';
                                break;
                                case 'public':
                                default:
                                    echo '<span class="label label-success">&nbsp;</span>';
                                break;
                         }
                    ?>

                    <?php echo $property['name']; ?> </a>
                <?php } ?>
             <?php } ?>
        <li class="list-group-item">Methods</li>
        <?php if (isset($methods)) { ?>
                <?php foreach ($methods as $method) {?>
                    <a href = "#m-<?php echo strtolower($method['name']); ?>" class="list-group-item">
                    <?php if(isset($method['visibility']))
                            switch ($method['visibility']) {
                                case 'private':
                                    echo '<span class="label label-danger">&nbsp;</span>';
                                break;
                                case 'protected':
                                    echo '<span class="label label-warning">&nbsp;</span>';
                                break;
                                case 'public':
                                default:
                                    echo '<span class="label label-success">&nbsp;</span>';
                                break;
                         }
                    ?>

                    <?php echo $method['name']; ?>()</a>
                <?php } ?>
             <?php } ?>
        </div>
    </div>
<?php $this->endBlock(); ?>
