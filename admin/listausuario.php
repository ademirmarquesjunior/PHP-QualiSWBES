<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <!-- <link rel="stylesheet" href="estilo.css" type="text/css" media="screen" /> -->
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/style.css" rel="stylesheet">
        <title>SEWebS</title>
</head>
<body>

<h1>Lista de questões</h1>
<p>
<a href="inserequestao.php"><img src="img/images.png" height="30" alt="Adicionar nova" /></a>
</p>

Ordenar por <a href="listausuario.php?order=1">Personagem</a>, <a href="listausuario.php?order=2">Artefato</a>, ou <a href="listausuario.php?order=3">Critério</a>.

<table class="table-bordered" >
<tr>
<td>ID</td>

<td>Personagem</td>
<td>Objetivo</td>
<td>Artefato</td>
<td>Critério</td>
<td>SubCritério</td>
<td>Questão</td>
<td>Como responder</td>
<td></td>
<td></td>

<?php
include "conecta.php";
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
			$artifact=$linha["tbArtifact_idtbArtifact"];
			$criterion=$linha["tbCriterion_idtbCriterion"];
			$subcriterion=$linha["tbSubCriterion_idtbSubCriterion"];
			$question=$linha["tbUserQuestionText"];
			$howto=$linha["tbUserQuestionHowTo"];
						
						
						
			echo  "<tr>";
			echo    "<td>".$id."</td>";
			echo    '<td><form method="post" action="">
<label><input name="Checkbox1" type="checkbox" value=""/>Personagem</label>
<label><input name="Checkbox1" type="checkbox" value=""/>Personagem</label>
<label><input name="Checkbox1" type="checkbox" value=""/>Personagem</label>
<label><input name="Checkbox1" type="checkbox" value=""/>Personagem</label>
</form>
';
			//$Sql2 = "SELECT * FROM `tbuserType` WHERE `idtbUserType` = ".$usertype;
			//$rs2 = mysql_query($Sql2, $conexao) or die ("Erro na pesquisa");
			//while ($linha2 =  mysql_fetch_array($rs2)){ echo $linha2["tbUserTypeDescripton"]; }			
			echo "</td>";
			
			
			echo    '<td><form method="post" action="">
<label><input name="Checkbox1" type="checkbox" value=""/>Objetivo</label>
<label><input name="Checkbox1" type="checkbox" value=""/>Objetivo</label>
<label><input name="Checkbox1" type="checkbox" value=""/>Objetivo</label>
<label><input name="Checkbox1" type="checkbox" value=""/>Objetivo</label>
</form>
';
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
			
			echo    "<td>";			
			$Sql2 = "SELECT * FROM `tbSubCriterion` WHERE `idtbSubCriterion` = ".$subcriterion;
			$rs2 = mysql_query($Sql2, $conexao) or die ("Erro na pesquisa");
			while ($linha2 =  mysql_fetch_array($rs2)){ echo $linha2["tbSubCriterionDesc"]; }			
			echo "</td>";

			
			
			echo   "<td>".$question."</td>";
			echo   "<td>".$howto."</td>";
			echo   '<td><a href="editaquestao.php?id='.$id.'&artifact='.$artifact.'&criterion='.$criterion.'&question='.$question.'&howto='.$howto.'"><img src="img/editar.png" alt="Editar" height="30"/></a></td>';
			echo   '<td><a href="deletaquestao.php?id='.$id.'&artifact='.$artifact.'&criterion='.$criterion.'&question='.$question.'&howto='.$howto.'"><img src="img/deletar.jpg" alt="Excluir" height="30"/></a></td>';
			echo  "</tr>";
			}
?>
</table>
</body>

</html>