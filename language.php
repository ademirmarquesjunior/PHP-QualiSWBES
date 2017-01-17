<?php
if (session_id() == '') {
	session_start();
}

if(isset($_GET['lang'])) {
	if ($_GET['lang'] == '1') {
		$_SESSION['language'] = '1';
	}	elseif($_GET['lang'] == '2') {
		$_SESSION['language'] = '2';
	} else {
		$_SESSION['language'] = '1';	
	}
	echo "<script> window.history.go(-1) </script>";	
}

if (!isset($_SESSION['language'])) {
	$_SESSION['language'] = 1;
}

?>
