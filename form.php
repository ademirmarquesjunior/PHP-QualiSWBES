<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="estilo.css" type="text/css" media="screen" />
<title></title>
</head>
<body>

<div id="header">
Cabeçalho aqui
</div>

<div id="login">
<?php
include("valida.php");
?>
</div>

<div id="content">

<h1>Questionário</h1>

<form action='form.php' method='get'>
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
						
						
			echo  "<p id='question'>".$question."</p>";
			echo  "<p id='howto'>".$howto."</p>";
			echo    "<input type='radio' name=".$id." value='0' required/>";
			echo    "<input type='radio' name=".$id." value='1' />";
			echo    "<input type='radio' name=".$id." value='2' />";
			echo    "<input type='radio' name=".$id." value='3' />";
			echo    "<input type='radio' name=".$id." value='4' />";
			echo    "<input type='radio' name=".$id." value='5' />";
			echo    "<hr>";

			}
						
?>

<input type='submit' value='Salvar'/>

</form>

</div>

<div id="footer">
Desenvolvimento: Ademir Marques Junior - 2016
</div>


</body>

</html>