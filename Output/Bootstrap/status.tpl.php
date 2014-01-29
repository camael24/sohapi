<?php
/**
 * @var Array $all
 * @var \Sohapi\Formatter\Html $html
 * @var \Sohapi\Greut $this
 */

$this->inherits('layout.tpl.php');
$this->block('container');

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
            }

            if ($c['status'] === 'not_exists') {
                $class = 'danger';
                $icon  = '<i class="fa fa-exclamation-triangle"></i>';
            }

            if ($c['status'] === 'ignored') {
                $class = 'warning';
                $icon  = '<i class="fa fa-exclamation-circle"></i>';
            }


            echo '<tr class="' . $class . '"><td>' . $icon . '</td>
            <td>' . $c['class'] . '</td>
            <td>' . $html->resolve($c['class']) . '</td>';
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