<?php
/**
 * @var String $classname
 * @var \Sohapi\Greut $this
 */

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <?php
    if (isset($title) and $title !== '')
        echo '<title>Sohapi | ' . $title . '</title>';
    else
        echo '<title>Sohapi</title>';
    ?>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-theme.css" rel="stylesheet">
    <link href="css/font-awesome.css" rel="stylesheet">
    <link href="css/jumbotron.css" rel="stylesheet">

</head>

<body>
<div class="container col-xs-12 col-sm-12">

    <?php
    $this->block('content');
    $this->endblock();
    ?>



    <footer>
        <p>&copy; Sohapi made by Camael24</p>
    </footer>
</div>
<!--/.container-->

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/bootstrap-treeview.js"></script>
<script src="js/app-treeview.js"></script>
<script src="js/app.js"></script>
</body>
</html>
