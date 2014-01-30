<?php
/**
 * @var Array $all
 * @var Int $valid
 * @var Int $error
 * @var \Sohapi\Formatter\Html $html
 * @var \Sohapi\Greut $this
 */

$this->inherits('layout.tpl.php');
$this->block('container');

echo '<div class="alert alert-success">We have parse <span class="label label-success">' . $valid . ' valid</span> files and <span class="label label-danger">' . $error . ' error</span>  files</div>';


?>

    <table class="table table-bordered">
        <thead>
        <tr class="active">
            <th>Status</th>
            <th>Classname</th>
            <th>Output file</th>
            <th>Nb Methodes</th>
            <th>Nb Propietes</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($all as $name => $c) {
            $class = '';
            $icon  = '';
            if ($c['status'] === 'success') {
                $class = 'success';
                $icon  = '<i class="fa fa-check"></i>';
            } else {
                $class = 'danger';
                $icon  = '<i class="fa fa-exclamation-circle"></i> ' . $c['status'];
            }


            echo '<tr class="' . $class . '"><td>' . $icon . '</td>
            <td>' . $c['class'] . '</td>
            <td><a href="' . $html->resolve($c['class']) . '">' . $html->resolve($c['class']) . '</a></td>';
            if (array_key_exists('methods', $c))
                echo '<td><span class="badge">' . count($c['methods']) . '</span></td>';
            else
                echo '<td><span class="badge"><i class="fa fa-question-circle"></i></span></td>';

            if (array_key_exists('properties', $c))
                echo '<td><span class="badge">' . count($c['properties']) . '</span></td>';
            else
                echo '<td><span class="badge"><i class="fa fa-question-circle"></i></span></td>';


            echo '</tr > ';
        }

        ?>
        </tbody>
    </table>



<?php

$this->endblock();
?>