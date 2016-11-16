<!DOCTYPE html>
<html><head>

<meta http-equiv="content-type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="estilo.css" type="text/css" media="screen"><title>Novo</title>

</head>

<body>
<h1>Inserção de questões</h1>
<form id="form1" name="form1" method="post" action="inserequestao.php">
<select name="sel_usuario" id="usuario">
<?php 
include "conecta.php";
$Sql = mysql_query("SELECT * FROM `tbusertype`");

while ($rr = mysql_fetch_array($Sql)) {
	echo "<option value=".$rr['idtbUserType'].">".$rr['tbUserTypeDescripton']."</option>";
	} ?></select>
	
<select name="sel_artifact" id="artifact"><?php $Sql = mysql_query("SELECT * FROM `tbArtifact`"); while ($rr = mysql_fetch_array($Sql)) { echo "<option value=".$rr['idtbArtifact'].">".$rr['tbArtifactDescription']."</option>"; } ?></select>
<select name="sel_criterion" id="criterion"><?php $Sql = mysql_query("SELECT * FROM `tbCriterion`"); while ($rr = mysql_fetch_array($Sql)) { echo "<option value=".$rr['idtbCriterion'].">".$rr['tbCriterionDesc']."</option>"; } ?></select>
<br>
<br>
<label>Questão</label><br>
<input name="txt_question" value="" type="text" size="100">
<br>
<label>Como responder</label><br>
<textarea cols="50" rows="10" name="howto"></textarea><br>

<input type="submit" name="Submit" value="Enviar" />
</form>


<?php

if ((isset($_POST['sel_usuario'])) && (isset($_POST['sel_artifact'])) && (isset($_POST['sel_criterion'])) && (isset($_POST['txt_question'])) && (isset($_POST['howto']))) {
	//nothing
}
else {
	exit;
}	

$usuario = trim($_POST['sel_usuario']);	
$artifact = trim($_POST['sel_artifact']);
$criterion = trim($_POST['sel_criterion']);	
$question = trim($_POST['txt_question']);
$howto = trim($_POST['howto']);	

$Sql = mysql_query("INSERT INTO `tbuserquestion` (`idtbUserQuestion`, `tbArtifact_idtbArtifact`, `tbCriterion_idtbCriterion`, `tbUserType_idtbUserType`, `tbUserQuestionText`, `tbUserQuestionHowTo`, `tbUserQuestionWeight`) VALUES (NULL, '".$artifact."', '".$criterion."', '".$usuario."', '".$question."', '".$howto."', '1');");

if ($Sql) {
	echo "<p>Inserido com sucesso</p>";
	echo "<p>Personagem: ".$usuario."; Artefato: ".$artifact."; Critério: ".$criterion."</p>";
	echo "<p>Questão: ".$question."</p>";
	echo "<p>Como responder: ".$howto."</p>";
}

?>



</body>



</html>