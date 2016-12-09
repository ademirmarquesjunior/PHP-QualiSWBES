<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>Untitled 1</title>
</head>

ALTER TABLE `tbform` ADD `tbformCompleted` BOOLEAN NOT NULL AFTER `tbformCompleted`;
TRUNCATE tbform_has_tbuserquestion;
TRUNCATE tbform;
TRUNCATE tbapplication;
<body>

<?php
include "conecta.php";



$Sql = "ALTER TABLE `tbform` ADD `tbformCompleted` BOOLEAN NOT NULL AFTER `tbUser_idtbUser`;TRUNCATE tbform_has_tbuserquestion;TRUNCATE tbform;TRUNCATE tbapplication;";
$rs = mysql_query($Sql, $conexao) or die ("Erro na pesquisa");

if ($rs) {
    echo "<script type='text/javascript'>";
    echo "alert('Alterado com sucesso')";
	echo "window.close();";
	echo "</script>";
} else {
	echo "erro";
}

?>

</body>

</html>
