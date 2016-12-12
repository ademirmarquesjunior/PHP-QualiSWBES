<span class='glyphicon glyphicon-user'></span>
<?php
//session_start();
// isset verifica se a sessão já existe
if(isset($_SESSION['user_login'])) {
echo "Bem vindo '".$_SESSION['user_login']."'. ";
echo "<a href='logout.php'>Sair</a>";
} else {
	echo "<script> window.location.assign('login.php')</script>";	
}
?>
