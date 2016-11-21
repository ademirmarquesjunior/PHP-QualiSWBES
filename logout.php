<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="" type="text/css" media="screen" />
<title>Sair</title>



</head>

<body>

<?php
session_start();
if(isset($_SESSION['user_login'])) {
//echo "Usuário '".$_SESSION['user_login']."' ";
session_destroy();
echo "<script language='javascript' type='text/javascript'> alert('Você saiu!'); window.location.href='login.php'; </script>";
} 
?>

</body>
</html>