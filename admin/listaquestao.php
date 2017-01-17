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

<select name="sel_Factor" id="Factor"class="form-control"><option value="" selected>Critério</option>
<?php 
$Sql = "SELECT * FROM `tbFactor`";
$rs = mysqli_query($conexao, $Sql);
while ($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
	echo "<option value=".$row['idtbFactor'].">".$row['tbFactorDesc']."</option>";
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
	$Factor = $_GET['sel_Factor'];
	
	if ($artifact != "") {
		$list = $list." AND tbartifact_idtbartifact = ".$artifact;
	}
	
	if ($Factor != "") {
		$list = $list." AND tbFactor_idtbFactor = ".$Factor;
	}
}



//$Sql = "SELECT * FROM tbuserquestion INNER JOIN tbquestiontext ON tbuserquestion.idtbUserQuestion = tbquestiontext.tbUserQuestion_idtbUserQuestion WHERE tbquestiontext.tbLanguage_idtbLanguage = 1";

$Sql = "SELECT * FROM tbuserquestion";

$rs = mysqli_query($conexao, $Sql) or die ("Erro na pesquisa");

		while($linha = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
			$id=$linha["idtbUserQuestion"];
			$artifact=$linha["tbArtifact_idtbArtifact"];
			$Factor=$linha["tbFactor_idtbFactor"];
			$subFactor=$linha["tbSubFactor_idtbSubFactor"];
			//$question=$linha["tbQuestionText"];
			//$howto=$linha["tbQuestionTextHowTo"];
						
						
						
			echo  "<tr>";
			echo    "<td>".$id."</td>";
			echo    '<td>';
			echo '<form method="get" action="editarelacaoquestao.php" class="form form-inline">';
			
			$Sql2 = "SELECT * FROM tbusertype INNER JOIN tbusertypetext ON tbusertype.idtbUserType = tbusertypetext.tbUserType_idtbUserType WHERE tbusertypetext.tbLanguage_idtbLanguage = 1";
			echo '<select name="sel_user" id="user" class="form-control" style="width: 35%">';
			echo '<option value="">Avaliador</option>';
			$rs2 = mysqli_query($conexao, $Sql2) or die ("Erro na pesquisa");
			while ($linha2 =  mysqli_fetch_array($rs2, MYSQLI_ASSOC)){ 
				echo "<option value=" . $linha2['idtbUserType'] . ">" . $linha2['tbUserTypeDesc'] . "</option>";
			}
			echo '</select>';
			
			echo '<input name="txt_question" value="'.$id.'" size="12" type="text" hidden/>';
			
			$Sql2 = "SELECT * FROM tbobjectives INNER JOIN tbobjectivestext ON tbobjectives.idtbObjectives = tbobjectivestext.tbObjectives_idtbObjectives WHERE tbobjectivestext.tbLanguage_idtbLanguage = 1";
			
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
			$Sql2 = "SELECT * FROM tbusertype INNER JOIN tbusertypetext ON tbusertype.idtbUserType = tbusertypetext.tbUserType_idtbUserType WHERE tbusertypetext.tbLanguage_idtbLanguage = 1";
			$rs2 = mysqli_query($conexao, $Sql2) or die ("Erro na pesquisa");
			while ($linha2 =  mysqli_fetch_array($rs2, MYSQLI_ASSOC)){ 
				echo "<strong>".$linha2['tbUserTypeDesc']. ":</strong> ";
				$Sql3 = "SELECT * FROM tbobjectives_has_tbuserquestion INNER JOIN tbobjectives ON tbobjectives_has_tbuserquestion.tbObjectives_idtbObjectives = tbobjectives.idtbObjectives INNER JOIN tbobjectivestext ON tbobjectives.idtbObjectives = tbobjectivestext.tbObjectives_idtbObjectives WHERE tbobjectivestext.tbLanguage_idtbLanguage = 1 AND tbobjectives_has_tbuserquestion.tbUserType_idtbUserType = ".$linha2['idtbUserType']." AND tbobjectives_has_tbuserquestion.tbUserQuestion_idtbUserQuestion = ".$id;
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
			$Sql2 = "SELECT * FROM tbartifact INNER JOIN tbartifacttext ON tbartifact.idtbartifact = tbartifacttext.tbartifact_idtbartifact WHERE tbartifacttext.tblanguage_idtblanguage = 1 AND  idtbartifact = ".$artifact;
			$rs2 = mysqli_query($conexao, $Sql2) or die ("Erro na pesquisa");
			while ($linha2 =  mysqli_fetch_array($rs2, MYSQLI_ASSOC)){ echo $linha2["tbArtifactName"]; }			
			echo "</td>";
			
			echo    "<td>";			
			$Sql2 = "SELECT * FROM tbFactor INNER JOIN tbfactortext ON tbfactor.idtbfactor = tbfactortext.tbfactor_idtbfactor WHERE tbfactortext.tblanguage_idtblanguage = 1 AND idtbFactor = ".$Factor;
			$rs2 = mysqli_query($conexao, $Sql2) or die ("Erro na pesquisa");
			while ($linha2 =  mysqli_fetch_array($rs2, MYSQLI_ASSOC)){ echo $linha2["tbFactorName"]; }			
			echo "</td>";
			
			echo    "<td>";			
			$Sql2 = "SELECT * FROM tbsubFactor INNER JOIN tbsubfactortext ON tbsubfactor.idtbsubfactor = tbsubfactortext.tbsubfactor_idtbsubfactor  WHERE tbsubfactortext.tblanguage_idtblanguage = 1 AND idtbsubFactor = ".$subFactor;
			$rs2 = mysqli_query($conexao, $Sql2) or die ("Erro na pesquisa");
			while ($linha2 =  mysqli_fetch_array($rs2, MYSQLI_ASSOC)){ echo $linha2["tbSubFactorName"]; }			
			echo "</td>";
	
			
			echo "<td>";
			$Sql2 = "SELECT * FROM tbquestiontext INNER JOIN tblanguage ON tbquestiontext.tbLanguage_idtbLanguage = tblanguage.idtbLanguage WHERE tbquestiontext.tbUserQuestion_idtbUserQuestion = ".$id;
			$rs2 = mysqli_query($conexao, $Sql2) or die ("Erro na pesquisa");
			while ($linha2 =  mysqli_fetch_array($rs2, MYSQLI_ASSOC)){ 
			echo $linha2["tbLanguageDesc"].": ";			
			echo $linha2["tbQuestionText"]."<hr>"; }
			echo "</td>";
			
			

			//echo   "<td>".$howto."</td>";
			echo   '<td><a href="editaquestao.php?id='.$id.'&artifact='.$artifact.'&Factor='.$Factor.'&subFactor='.$subFactor.'"><img src="img/editar.png" alt="Editar" height="30"/></a></td>';
			echo   '<td><a href="deletaquestao.php?id='.$id.'&artifact='.$artifact.'&Factor='.$Factor.'&subFactor='.$subFactor.'"><img src="img/deletar.jpg" alt="Excluir" height="30"/></a></td>';
			echo  "</tr>";
			}
?>
</table>
</body>

</html>