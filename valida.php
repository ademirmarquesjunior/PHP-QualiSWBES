<?php
if(!isset($_SESSION['user_login'])) {
	echo "<script> window.location.assign('login.php')</script>";	
}
?>
