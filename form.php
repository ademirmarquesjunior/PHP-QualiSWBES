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

<!-- Ordenar por <a href="listaquestao.php?order=1">Personagem<a/>, <a href="listaquestao.php?order=2">Artefato<a/>, ou <a href="listaquestao.php?order=3">Critério<a/>. -->

<form action='form.php' method='get'>
<?php
include "conecta.php";
//echo $_SERVER['QUERY_STRING'];
echo "<hr />";

/*verificar se o formulário já foi preenchido antes */

foreach ($_GET as $key => $value) {
    echo "<p>".$key."</p>"; //id da questão na tabela de questões
    echo "<p>".$value."</p>"; //resposta
	/*
	Outras variáveis devem vir das variáveis de sessão definidas na sessão anterior
	id do usuário
	id da aplicação do usuário
	id do formulário
	
	Variáveis da tabela tbuserquestion
	artefato
	criterio
	subcriterio
	tipo de usuário
	
	
	*/
	
    echo "<hr />"; 
} 

/*
Depois de salvar as questões no formulário x questão ir para a página de resultados


*/




$order = "";

if (isset($_GET["order"])) {
	if (htmlspecialchars($_GET["order"]) == "1") {
		$order = "ORDER BY `tbUserType_idtbUserType`";
	} ELSEIF (htmlspecialchars($_GET["order"]) == "2") {
		$order = "ORDER BY `tbArtifact_idtbArtifact`";
	} ELSEIF (htmlspecialchars($_GET["order"]) == "3") {
		$order = "ORDER BY `tbCriterion_idtbCriterion`";
	} ELSE {
		$order = "";
	}
}





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
</body>

</html>