<?php session_start(); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <script src="dist/sweetalert.js"></script>
    	<link rel="stylesheet" href="dist/sweetalert.css">
        <title><?php echo $lang['PAGE_TITLE']; ?></title>
	</head>
	<body>

<?php
	if(isset($_SESSION['user_login'])) {
		session_destroy();
		echo "<script> window.location.assign('index.php')</script>";
	} 
?>
	</body>
</html>