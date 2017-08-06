<?php
if (session_id() == '') {
	session_start();
}



if (!isset($_SESSION['language'])) {
	$_SESSION['language'] = 1;
	$lang_file = 'lang.br.php';
} else {
	if ($_SESSION['language'] == 1) {
		$lang_file = 'lang.br.php';
	} elseif($_SESSION['language'] == 2) {
		$lang_file = 'lang.en.php';
	} else {
		$lang_file = 'lang.br.php';
	}
	
}

include $lang_file;

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
?>
