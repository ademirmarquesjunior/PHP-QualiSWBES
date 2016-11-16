<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="estilo.css" type="text/css" media="screen" />
<title></title>
</head>
<body>

<h1>Lista de questões</h1>
<p>
<a href="inserequestao.php">Inserir nova<a/>
</p>

Ordenar por <a href="listaquestao.php?order=1">Personagem<a/>, <a href="listaquestao.php?order=2">Artefato<a/>, ou <a href="listaquestao.php?order=3">Critério<a/>.

<table border=1 >
<tr>
<td>ID</td>
<td>Personagem</td>
<td>Artefato</td>
<td>Critério</td>
<td>Questão</td>
<td>Como responder</td>
<td></td>
<td></td>

<?php
include "conecta.php";

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
						
						
						
			echo  "<tr>";
			echo    "<td>".$id."</td>";
			echo    "<td>";
			$Sql2 = "SELECT * FROM `tbuserType` WHERE `idtbUserType` = ".$usertype;
			$rs2 = mysql_query($Sql2, $conexao) or die ("Erro na pesquisa");
			while ($linha2 =  mysql_fetch_array($rs2)){ echo $linha2["tbUserTypeDescripton"]; }			
			echo "</td>";
			
			echo    "<td>";			
			$Sql2 = "SELECT * FROM `tbArtifact` WHERE `idtbArtifact` = ".$artifact;
			$rs2 = mysql_query($Sql2, $conexao) or die ("Erro na pesquisa");
			while ($linha2 =  mysql_fetch_array($rs2)){ echo $linha2["tbArtifactDescription"]; }			
			echo "</td>";
			
			echo    "<td>";			
			$Sql2 = "SELECT * FROM `tbCriterion` WHERE `idtbCriterion` = ".$criterion;
			$rs2 = mysql_query($Sql2, $conexao) or die ("Erro na pesquisa");
			while ($linha2 =  mysql_fetch_array($rs2)){ echo $linha2["tbCriterionDesc"]; }			
			echo "</td>";
			
			
			echo   "<td>".$question."</td>";
			echo   "<td>".$howto."</td>";
			echo   '<td><form><input type="submit" value="Editar"/></form></td>';
			echo   '<td><form><input type="submit" value="Excluir"/></form></td>';
			echo  "</tr>";
			}
?>
</table>
</body>

</html>