<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>

<body>

<?php
session_start();
if(isset($_SESSION['user_login'])) {
echo "Usuário '".$_SESSION['user_login']."' ";
session_destroy();
echo "Desconectado com sucesso";
} 
?>
<a href='login.php'>login</a>

</body>
</html>