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
    if (isset($classname))
        if ($classname !== '')
            echo '<title>Sohapi | ' . $classname . '</title>';
        else
            echo '<title>Sohapi</title>';
    ?>



    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/offcanvas.css" rel="stylesheet">

</head>

<body>
<div class="container">

    <?php
    $this->block('container');
    $this->endblock();
    ?>

    <hr>

    <footer>
        <p>&copy; Sohapi made by Camael24</p>
    </footer>

</div>
<!--/.container-->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/offcanvas.js"></script>
</body>
</html>
