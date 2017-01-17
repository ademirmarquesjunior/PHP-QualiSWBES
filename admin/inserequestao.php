<!DOCTYPE html>
<html><head>

<meta http-equiv="content-type" content="text/html; charset=utf-8">
        <!-- <link rel="stylesheet" href="estilo.css" type="text/css" media="screen" /> -->
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/style.css" rel="stylesheet">
        <title>SEWebS</title>

</head>
<body>

<?php
include "conecta.php";

if ((isset($_POST['SubmitPort'])) || (isset($_POST['SubmitPort']))) {
	
	$artifact = trim($_POST['sel_artifact']);
	$factor = trim($_POST['sel_factor']);
	$subfactor = trim($_POST['sel_subfactor']);
	$question = trim($_POST['txt_question']);
	$howto = trim($_POST['howto']);	

	
	
	//$Sql = "INSERT INTO `tbuserquestion` (`idtbUserQuestion`, `tbArtifact_idtbArtifact`, `tbFactor_idtbFactor`, `tbSubFactor_idtbSubFactor`) VALUES (NULL, '".$artifact."', '".$factor."',  '".$subfactor."')";
	$Sql = "INSERT INTO `tbuserquestion` (`idtbUserQuestion`, `tbArtifact_idtbArtifact`, `tbFactor_idtbFactor`, `tbSubFactor_idtbSubFactor`) VALUES (NULL, '".$artifact."', '".$factor."', '".$subfactor."')";

	$rs = mysqli_query($conexao, $Sql);
	
	$row = mysqli_insert_id($conexao);
	echo $row;

	if ($rs) {
		if (isset($_POST['SubmitPort'])) {
			$language = 1;
		}
		
		if (isset($_POST['SubmitEng'])) {
			$language = 2;
		
		}		
		$Sql = "INSERT INTO `tbquestiontext` (`tbQuestionText`, `tbQuestionTextHowTo`, `tbLanguage_idtbLanguage`, `tbUserQuestion_idtbUserQuestion`) VALUES ('".$question."', '".$howto."', '".$language."', '".$row."')";

		$rs = mysqli_query($conexao, $Sql);
		echo $Sql;
		
		if ($rs) {
			echo "<script language='javascript' type='text/javascript'> alert('Inserido com sucesso!'); </script>";
		}
	
	} else {
		echo "<script language='javascript' type='text/javascript'> alert('Erro!'); </script>";
		
	}
}
?>


<h1>Inserir nova questão</h1>
<p><a href='listaquestao.php'><img src='img/voltar.png' alt='Voltar para lista' height='30'/></a></p>

<form id="form1" name="form1" method="post" action="inserequestao.php" class="form-inline">
<!-- <select name="sel_usuario" id="usuario"><?php $Sql = mysqli_query("SELECT * FROM `tbusertype`"); while ($rr = mysql_fetch_array($Sql)) { echo "<option value=".$rr['idtbUserType'].">".$rr['tbUserTypeDescripton']."</option>"; } ?></select> -->

<select name="sel_artifact" id="artifact" class="form-control"><option value="">Artefato</option>
<?php 
$Sql = "SELECT * FROM tbartifact INNER JOIN tbartifacttext ON tbartifact.idtbArtifact = tbartifacttext.tbArtifact_idtbArtifact WHERE tbartifacttext.tbLanguage_idtbLanguage = 1";
$rs = mysqli_query($conexao, $Sql);
while ($row = mysqli_fetch_array($rs, MYSQL_ASSOC)) {
	echo "<option value=".$row['idtbArtifact'].">".$row['tbArtifactName']."</option>";
} ?>
</select>

<select name="sel_factor" id="factor"class="form-control"><option value="">Fator</option>
<?php 
$Sql = "SELECT * FROM tbfactor INNER JOIN tbfactortext ON tbfactor.idtbFactor = tbfactortext.tbFactor_idtbFactor WHERE tbfactortext.tbLanguage_idtbLanguage = 1";
$rs = mysqli_query($conexao, $Sql);
while ($row = mysqli_fetch_array($rs, MYSQL_ASSOC)) {
	echo "<option value=".$row['idtbFactor'].">".$row['tbFactorName']."</option>";
} ?>
</select>

<select name="sel_subfactor" id="subfactor"class="form-control"><option value="">Subfator</option>
<?php 
$Sql = "SELECT * FROM tbsubfactor INNER JOIN tbsubfactortext ON tbsubfactor.idtbsubFactor = tbsubfactortext.tbsubFactor_idtbsubFactor WHERE tbsubfactortext.tbLanguage_idtbLanguage = 1 ORDER BY tbsubfactortext.tbSubFactorName";
$rs = mysqli_query($conexao, $Sql);
while ($row = mysqli_fetch_array($rs, MYSQL_ASSOC)) {
	echo "<option value=".$row['idtbSubFactor'].">".$row['tbSubFactorName']."</option>";
} ?>
</select>

<br>
<!-- <select name="sel_objective" id="objective"><?php $Sql = mysqli_query("SELECT * FROM `tbObjectives`"); while ($rr = mysql_fetch_array($Sql)) { echo "<option value=".$rr['idtbObjectives'].">".$rr['tbObjectivesDesc']."</option>"; } ?></select>
<input name="txt_weight" type="text" value="1" class="form-control"/> -->
<br>
<label>Questão</label><br>
<input name="txt_question" value="" type="text" size="100" class="form-control">
<br>
<label>Como responder</label><br>
<textarea cols="50" name="howto" class="form-control" style="height: 119px"></textarea><br>

<input type="submit" name="SubmitPort" value="Português" class="btn btn-default"/>
<input type="submit" name="SubmitEng" value="Inglês" class="btn btn-default"/>
<p><a href='listaquestao.php'><img src='img/voltar.png' alt='Voltar para lista' height='30'/></a></p>
</form>





</body>



</html>