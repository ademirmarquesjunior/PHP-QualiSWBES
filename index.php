<?php session_start(); 
	include "language.php";
?>
<html>
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="content-type" />
        <meta name="description" content="Abordagem para avaliação da qualidade de sistemas educacionais baseados em Web Semântica, Approach for quality evaluation of educational systems based on Semantic Web">
        <meta name="keywords" content="Web Semântica, educação, qualidade, avaliação, abordagem, Semantic Web, educational, quality, assesment, approach, education, USP, ICMC, SWBES">
        <meta name="author" content="Aparecida Maria Zem Lopes, Ademir Marques Junior, Seiji Isotani">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/sweetalert.css">
        <script src="js/jquery-3.1.1.min.js"></script>        
        <script src="js/bootstrap.min.js"></script>
		<script src="js/sweetalert.js"></script>
        <link rel="icon" type="image/png" href="favicon.png">
        <title><?php echo $lang['PAGE_TITLE']; ?></title>
    </head>
<body>
	<div class="container-fluid">
<?php
	include 'header.php';
	include 'navbar.php';
?>
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
			</div>
		</div>
<?php
	include 'footer.php';
?>
			</div>
		</div>
	</body>
</html>
