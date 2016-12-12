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

if ((isset($_POST['sel_artifact'])) && (isset($_POST['sel_criterion'])) && (isset($_POST['txt_question'])) && (isset($_POST['howto']))) {
	//nothing

	

	//$usuario = trim($_POST['sel_usuario']);	
	$artifact = trim($_POST['sel_artifact']);
	$criterion = trim($_POST['sel_criterion']);
	$subcriterion = trim($_POST['sel_subcriterion']);
	$question = trim($_POST['txt_question']);
	$howto = trim($_POST['howto']);	
	//$objective = trim($_POST['sel_objective']);
	$weight = trim($_POST['txt_weight']);	
	
	
	$Sql = "INSERT INTO `tbuserquestion` (`idtbuserquestion`, `tbartifact_idtbartifact`, `tbcriterion_idtbcriterion`, `tbsubcriterion_idtbsubcriterion`, `tbuserquestiontext`, `tbuserquestionhowto`, `tbuserquestionweight`) VALUES (NULL, '".$artifact."', '".$criterion."',  '".$subcriterion."', '".$question."', '".$howto."', '1');";
	$rs = mysqli_query($conexao, $rs);

	if ($rs) {
			echo "<script language='javascript' type='text/javascript'> alert('Inserido com sucesso!'); </script>";
	
	}
}
?>


<h1>Inserir nova questão</h1>
<p><a href='listaquestao.php'><img src='img/voltar.png' alt='Voltar para lista' height='30'/></a></p>

<form id="form1" name="form1" method="post" action="inserequestao.php" class="form-inline">
<!-- <select name="sel_usuario" id="usuario"><?php $Sql = mysqli_query("SELECT * FROM `tbusertype`"); while ($rr = mysql_fetch_array($Sql)) { echo "<option value=".$rr['idtbUserType'].">".$rr['tbUserTypeDescripton']."</option>"; } ?></select> -->

<select name="sel_artifact" id="artifact" class="form-control"><option value="">Artefato</option>
<?php 
$Sql = "SELECT * FROM `tbartifact`";
$rs = mysqli_query($conexao, $Sql);
while ($row = mysqli_fetch_array($rs, MYSQL_ASSOC)) {
	echo "<option value=".$row['idtbArtifact'].">".$row['tbArtifactDescription']."</option>";
} ?>
</select>

<select name="sel_criterion" id="criterion"class="form-control"><option value="">Critério</option>
<?php 
$Sql = "SELECT * FROM `tbcriterion`";
$rs = mysqli_query($conexao, $Sql);
while ($row = mysqli_fetch_array($rs, MYSQL_ASSOC)) {
	echo "<option value=".$row['idtbCriterion'].">".$row['tbCriterionDesc']."</option>";
} ?>
</select>

<select name="sel_subcriterion" id="subcriterion"class="form-control"><option value="">Subcritério</option>
<?php 
$Sql = "SELECT * FROM `tbsubcriterion` ORDER BY `tbsubcriteriondesc`";
$rs = mysqli_query($conexao, $Sql);
while ($row = mysqli_fetch_array($rs, MYSQL_ASSOC)) {
	echo "<option value=".$row['idtbSubCriterion'].">".$row['tbSubCriterionDesc']."</option>";
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

<input type="submit" name="Submit" value="Enviar" class="btn btn-default"/>
<p><a href='listaquestao.php'><img src='img/voltar.png' alt='Voltar para lista' height='30'/></a></p>
</form>





</body>



</html>