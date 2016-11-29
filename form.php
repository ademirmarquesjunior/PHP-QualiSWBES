<!DOCTYPE html>
<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="content-type" />
<!-- <link rel="stylesheet" href="estilo.css" type="text/css" media="screen" /> -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<title></title>
</head>

<body>

<div class="container-fluid">
	<div class="jumbotron">
		<h2>Modelo de Avaliação de Qualidade dos Sistemas Educacionais
baseados em Web Semântica (SEWebS) </h2>
	</div>
	<div id="login" class="well well-sm">
		<?php
include("valida.php");
?></div>
	<h1>Questionário</h1>
	<form action="form.php" method="get" class="form-group">
		<?php
include "conecta.php";


$inserted = 0;
/*verificar se o formulário já foi preenchido antes */
foreach ($_GET as $key => $value) {
    echo "<p>".$key."</p>"; //id da questão na tabela de questões
    echo "<p>".$value."</p>"; //resposta
    echo "<p>".$_SESSION['form_id']."</p>";
    
    $Sql = "INSERT INTO `tbform_has_tbuserquestion` (`tbForm_idtbForm`, `tbUserQuestion_idtbUserQuestion`, `tbForm_has_tbUserQuestionAnswer`) VALUES ('".$_SESSION['form_id']."', '".$key."', '".$value."')";
    $rs = mysql_query($Sql, $conexao) or die ("Erro insere formulário");
    $inserted = 1;
} 

if ($inserted == 1) {
    header('Location:results.php?form='.$_SESSION['form_id']);
}

$order = "ORDER BY tbArtifact_idtbArtifact";

//$Sql = "SELECT * FROM `tbuserquestion` WHERE tbusertype_idtbUsertype = '".$_SESSION['user_type']."' ".$order;
$Sql = "SELECT * FROM `tbuserquestion` ".$order;
$rs = mysql_query($Sql, $conexao) or die ("Erro na pesquisa");

		while($linha = mysql_fetch_array($rs))
		{
			$id=$linha["idtbUserQuestion"];
			$usertype=$linha["tbUserType_idtbUserType"];
			$artifact=$linha["tbArtifact_idtbArtifact"];
			$criterion=$linha["tbCriterion_idtbCriterion"];
			$question=$linha["tbUserQuestionText"];
			$howto=$linha["tbUserQuestionHowTo"];
						
						
			echo  "<h4 id='question'>".$question."</h4>";
			echo  "<h5 id='howto'>".$howto."</h5>";
			echo    "<div class='radio'><label><input type='radio' name=".$id." value='0' class='optradio' required/>0</label></div>";
			echo    "<div class='radio'><label><input type='radio' name=".$id." value='1' class='optradio' />1</label></div>";
			echo    "<div class='radio'><label><input type='radio' name=".$id." value='2' class='optradio'  />2</label></div>";
			echo    "<div class='radio'><label><input type='radio' name=".$id." value='3' class='optradio' />3</label></div>";
			echo    "<div class='radio'><label><input type='radio' name=".$id." value='4' class='optradio' />4</label></div>";
			echo    "<div class='radio'><label><input type='radio' name=".$id." value='5' class='optradio' />5</label></div>";
			echo    "<hr>";

			}
						
?><input type="submit" value="Salvar" class="btn btn-default"/>
	</form>
	<div id="footer" class="well well-sm">
		Desenvolvimento: Ademir Marques Junior - 2016 </div>
</div>
</body>

</html>
