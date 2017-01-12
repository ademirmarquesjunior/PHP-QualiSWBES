<!DOCTYPE html>
<html>
<head>
        <meta content="text/html; charset=utf-8" http-equiv="content-type" />
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
<select name="sel_artifact" id="artifact" class="form-control"><option value="" selected>Artefato</option>
<?php
$Sql = "SELECT * FROM `tbartifact`";
$rs = mysqli_query($conexao, $Sql);
while ($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
	echo "<option value=".$row['idtbArtifact'].">".$row['tbArtifactDescription']."</option>";
} ?></select>

<select name="sel_criterion" id="criterion"class="form-control"><option value="" selected>Critério</option>
<?php 
$Sql = "SELECT * FROM `tbcriterion`";
$rs = mysqli_query($conexao, $Sql);
while ($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
	echo "<option value=".$row['idtbCriterion'].">".$row['tbCriterionDesc']."</option>";
} ?></select>
<input type="submit" name="submitlistar" value="Listar" class="btn btn-default"/>
</form>


<table class="table table-condensed table-hover" >
<tr>
<td>ID</td>

<td style="width: 440px">Avaliador X Objetivo</td>
<td>Artefato</td>
<td>Fator</td>
<td style="width: 65px">SubFator</td>
<td>Critério</td>
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
		$list = $list." AND tbartifact_idtbartifact = ".$artifact;
	}
	
	if ($criterion != "") {
		$list = $list." AND tbcriterion_idtbcriterion = ".$criterion;
	}
}



$Sql = "SELECT * FROM `tbuserquestion` ".$list;
$rs = mysqli_query($conexao, $Sql) or die ("Erro na pesquisa");

		while($linha = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
			$id=$linha["idtbUserQuestion"];
			$artifact=$linha["tbArtifact_idtbArtifact"];
			$criterion=$linha["tbCriterion_idtbCriterion"];
			$subcriterion=$linha["tbSubCriterion_idtbSubCriterion"];
			$question=$linha["tbUserQuestionText"];
			$howto=$linha["tbUserQuestionHowTo"];
						
						
						
			echo  "<tr>";
			echo    "<td>".$id."</td>";
			echo    '<td>';
			echo '<form method="get" action="editarelacaoquestao.php" class="form form-inline">';
			
			$Sql2 = "SELECT * FROM `tbusertype`";
			echo '<select name="sel_user" id="user" class="form-control" style="width: 35%">';
			echo '<option value="">Avaliador</option>';
			$rs2 = mysqli_query($conexao, $Sql2) or die ("Erro na pesquisa");
			while ($linha2 =  mysqli_fetch_array($rs2, MYSQLI_ASSOC)){ 
				echo "<option value=" . $linha2['idtbUserType'] . ">" . $linha2['tbUserTypeDescripton'] . "</option>";
			}
			echo '</select>';
			
			echo '<input name="txt_question" value="'.$id.'" size="12" type="text" hidden/>';
			
			$Sql2 = "SELECT * FROM `tbobjectives`";
			echo '<select name="sel_objective" id="objective" class="form-control" style="width: 35%">';
			echo '<option value="">Objetivo</option>';
			$rs2 = mysqli_query($conexao, $Sql2) or die ("Erro na pesquisa");
			while ($linha2 =  mysqli_fetch_array($rs2, MYSQLI_ASSOC)){ 
				echo "<option value=".$linha2['idtbObjectives'].">".$linha2['tbObjectivesDesc']."</option>";
			}
			echo '</select>';
			echo '<input name="txt_weight" value="1.00" size="1" type="text" class="form-control"/>';
			echo '<button type="submit" name="submitRelationDel"><span class="glyphicon glyphicon-minus-sign"></span></button>';
			echo '<button type="submit" name="submitRelationAdd"><span class="glyphicon glyphicon-plus-sign"></span></button></form>';
			
			echo '<small>';
			$Sql2 = "SELECT * FROM `tbusertype`";
			$rs2 = mysqli_query($conexao, $Sql2) or die ("Erro na pesquisa");
			while ($linha2 =  mysqli_fetch_array($rs2, MYSQLI_ASSOC)){ 
				echo "<strong>".$linha2['tbUserTypeDescripton']. ":</strong> ";
				$Sql3 = "SELECT * FROM tbobjectives_has_tbuserquestion INNER JOIN tbobjectives ON tbobjectives_has_tbuserquestion.tbObjectives_idtbObjectives = tbobjectives.idtbObjectives WHERE tbobjectives_has_tbuserquestion.tbUserType_idtbUserType = ".$linha2['idtbUserType']." AND tbobjectives_has_tbuserquestion.tbUserQuestion_idtbUserQuestion = ".$id;
				//echo $Sql3;
				$rs3 = mysqli_query($conexao, $Sql3) or die ("Erro na pesquisa");
				while ($linha3 =  mysqli_fetch_array($rs3, MYSQLI_ASSOC)){ 
					echo $linha3['tbObjectivesDesc']."(".$linha3['tbObjectives_has_tbUserWeight'].");";
				}
				echo ".<br>";
			}
			echo '</small>';			
			echo "</td>";

			echo    "<td>";			
			$Sql2 = "SELECT * FROM tbartifact WHERE idtbartifact = ".$artifact;
			$rs2 = mysqli_query($conexao, $Sql2) or die ("Erro na pesquisa");
			while ($linha2 =  mysqli_fetch_array($rs2, MYSQLI_ASSOC)){ echo $linha2["tbArtifactDescription"]; }			
			echo "</td>";
			
			echo    "<td>";			
			$Sql2 = "SELECT * FROM tbcriterion WHERE idtbcriterion = ".$criterion;
			$rs2 = mysqli_query($conexao, $Sql2) or die ("Erro na pesquisa");
			while ($linha2 =  mysqli_fetch_array($rs2, MYSQLI_ASSOC)){ echo $linha2["tbCriterionDesc"]; }			
			echo "</td>";
			
			echo    "<td>";			
			$Sql2 = "SELECT * FROM tbsubcriterion WHERE idtbsubcriterion = ".$subcriterion;
			$rs2 = mysqli_query($conexao, $Sql2) or die ("Erro na pesquisa");
			while ($linha2 =  mysqli_fetch_array($rs2, MYSQLI_ASSOC)){ echo $linha2["tbSubCriterionDesc"]; }			
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