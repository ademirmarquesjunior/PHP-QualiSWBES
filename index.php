<?php session_start(); 
include "language.php";
?>
<html>
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="content-type" />
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="slider/rzslider.css"/>
        <link rel="stylesheet" href="css/sweetalert.css">
        <script src="js/jquery-3.1.1.min.js"></script>        
        <script src="js/bootstrap.min.js"></script>
			<script src="js/sweetalert.js"></script>
			<script src="slider/angular.min.js"></script>
			<script src="slider/rzslider.min.js"></script>
        <link rel="icon" type="image/png" href="favicon.png">
        <title>Avalia SEWebS</title>
    </head>
<body>

<div class="container-fluid">
            <?php
            include 'header.php';
            include 'navbar.php';
            ?>



			<!-- <div><h1>Abordagem para Avaliação de Qualidade de Sistemas Educacionais baseados em Web Semântica<br>QASWebES</h1></div> -->
			<div class="panel panel-default">
			<div class='panel-heading'><h1><?php echo $lang['MENU_INTRODUCTION'] ?></h1></div>
			<div class="panel-body">
					<div class="row">
						<div class="col-md-8">
							<?php echo $lang['INDEX_TEXT']; ?>
						</div>
						<div class="col-md-4">
							<img src="img/overview.jpg" width="100%" alt="" align="right">
						</div>
					</div>
			<!-- <a class="btn btn-primary btn-large" href="index2.php">Começar uma avaliação</a>
			 -->
		</div>
		
	</div>
            <?php
            include 'footer.php';
            ?>
	</div>
</div>



</body>

</html>
