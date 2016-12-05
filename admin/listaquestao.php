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
<?php
include "conecta.php";
?>
<h1>Gerenciamento de questões</h1>
<p>
<a href="inserequestao.php"><img src="img/images.png" height="30" alt="Adicionar nova" />Inserir nova</a>
</p>

<form id="form2" name="form2" method="get" action="listaquestao.php" class="form-inline">
<select name="sel_artifact" id="artifact" class="form-control"><option value="" selected>Artefato</option><?php $Sql = mysql_query("SELECT * FROM `tbArtifact`"); while ($rr = mysql_fetch_array($Sql)) { echo "<option value=".$rr['idtbArtifact'].">".$rr['tbArtifactDescription']."</option>"; } ?></select>
<select name="sel_criterion" id="criterion"class="form-control"><option value="" selected>Critério</option><?php $Sql = mysql_query("SELECT * FROM `tbCriterion`"); while ($rr = mysql_fetch_array($Sql)) { echo "<option value=".$rr['idtbCriterion'].">".$rr['tbCriterionDesc']."</option>"; } ?></select>
<input type="submit" name="submitlistar" value="Listar" class="btn btn-default"/>
</form>


<table class="table table-condensed table-hover" >
<tr>
<td>ID</td>

<td>Personagem</td>
<td style="width: 269px">Objetivo</td>
<td>Artefato</td>
<td>Critério</td>
<td style="width: 65px">SubCritério</td>
<td>Questão</td>
<!--<td>Como responder</td>-->
<td></td>
<td></td>

<?php
//include "conecta.php";
$list = "WHERE 1=1 ";

if (isset($_GET["submitlistar"])) {
	$artifact = $_GET['sel_artifact'];
	$criterion = $_GET['sel_criterion'];
	
	if ($artifact != "") {
		$list = $list." AND tbArtifact_idtbArtifact = ".$artifact;
	}
	
	if ($criterion != "") {
		$list = $list." AND tbCriterion_idtbCriterion = ".$criterion;
	}
}

$Sql = "SELECT * FROM `tbuserquestion` ".$list;
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
			echo    '<td>';
			echo '<form method="get" target="_blank" action="editarelacaoquestao.php">';
			$Sql2 = "SELECT * FROM `tbusertype`";
			$rs2 = mysql_query($Sql2, $conexao) or die ("Erro na pesquisa");
			while ($linha2 =  mysql_fetch_array($rs2)){ 
					
				echo '<label><input name="Checkbox1[]" type="checkbox" value="'.$linha2["idtbUserType"].'"';
				$Sql3 = "SELECT * FROM `tbusertype_has_tbUserquestion` WHERE `tbUserQuestion_idtbUserQuestion` = ".$id." AND `tbUserType_idtbUsertype` = ".$linha2["idtbUserType"];
				//echo $Sql3;
				$rs3 = mysql_query($Sql3, $conexao);
				while ($linha3 =  mysql_fetch_array($rs3)){				
					echo 'checked="checked" ';
				}
				echo '/>'.$linha2["tbUserTypeDescripton"].'</label><br>';
				
			}
			echo '<input name="txt_questao" value="'.$id.'" size="12" type="text" hidden/>';	
			echo '<button type="submit" name="submituser"><span class="glyphicon glyphicon-refresh"></span></button></form>';
			echo "</td>";

			echo    '<td>';
			echo '<form method="get" target="_blank" action="editarelacaoobjetivo.php">';
			$Sql2 = "SELECT * FROM `tbobjectives`";
			$rs2 = mysql_query($Sql2, $conexao) or die ("Erro na pesquisa");
			while ($linha2 =  mysql_fetch_array($rs2)){ 
				$weight = 0;
				echo '<label><input name="Checkbox2[]" type="checkbox" value="'.$linha2["idtbObjectives"].'"';
				$Sql3 = "SELECT * FROM `tbobjectives_has_tbUserquestion` WHERE `tbUserQuestion_idtbUserQuestion` = ".$id." AND `tbobjectives_idtbobjectives` = ".$linha2["idtbObjectives"];
				//echo $Sql3;
				$rs3 = mysql_query($Sql3, $conexao);
				while ($linha3 =  mysql_fetch_array($rs3)){	
					$weight = $linha3["tbObjectives_has_tbUserWeight"];		
					echo 'checked="checked" ';
				}
				echo '/>'.$linha2["tbObjectivesDesc"].'</label>';
				echo '<input name="txt_peso[]" value="'.$weight.'" size="2" type="text"/><br>';
				
			}
			echo '<input name="txt_questao" value="'.$id.'" size="12" type="text" hidden/>';	
			echo '<button type="submit" name="submitobjective"><span class="glyphicon glyphicon-refresh"></span></button></form>';
						
			
			
			
			
			echo '</td>';

			
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
			//echo   "<td>".$howto."</td>";
			echo   '<td><a href="editaquestao.php?id='.$id.'&artifact='.$artifact.'&criterion='.$criterion.'&subcriterion='.$subcriterion.'&question='.$question.'&howto='.$howto.'"><img src="img/editar.png" alt="Editar" height="30"/></a></td>';
			echo   '<td><a href="deletaquestao.php?id='.$id.'&artifact='.$artifact.'&criterion='.$criterion.'&subcriterion='.$subcriterion.'&question='.$question.'&howto='.$howto.'"><img src="img/deletar.jpg" alt="Excluir" height="30"/></a></td>';
			echo  "</tr>";
			}
?>
</table>
</body>

</html>