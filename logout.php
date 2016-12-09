<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <script src="dist/sweetalert.js"></script>
    	<link rel="stylesheet" href="dist/sweetalert.css">
        <title>Avalia SEWebS</title>



</head>

<body>

<?php
session_start();
if(isset($_SESSION['user_login'])) {
//echo "Usuário '".$_SESSION['user_login']."' ";
session_destroy();
//echo "<script language='javascript' type='text/javascript'> swal('Você saiu!'); window.location.href='index.php'; </script>";
header('Location:index.php');
} 
?>

</body>
</html>