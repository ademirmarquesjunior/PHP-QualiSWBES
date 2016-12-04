<!DOCTYPE html>
<html><head>

<meta http-equiv="content-type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="estilo.css" type="text/css" media="screen"><title>Novo</title>

</head>

<body>

<?php
include "conecta.php";



$Sql = "SELECT * FROM `tbUserType` ";
$rs = mysql_query($Sql, $conexao) or die ("Erro na pesquisa-user");

		while($linha = mysql_fetch_array($rs))
		{
			$usertype_id=$linha["idtbUserType"];
			$usertype=$linha["tbUserTypeDescripton"];
		
			echo $usertype."<br>";
			
			$Sql2 = "SELECT * FROM `tbusertype_has_tbartifact` INNER JOIN tbartifact ON `tbartifact`.`idtbartifact` = `tbusertype_has_tbartifact`.`tbartifact_idtbartifact` WHERE `tbusertype_has_tbartifact`.`tbUserType_idtbUserType` = ".$usertype_id;
			$rs2 = mysql_query($Sql2, $conexao);	

			while($linha2 = mysql_fetch_array($rs2))
			{
				$artifact_id=$linha2["idtbArtifact"];
				$artifact=$linha2["tbArtifactDescription"];
			
				echo "*".$artifact."<br>";
				
				$Sql3 = "SELECT * FROM `tbartifact_has_tbcriterion` INNER JOIN tbcriterion ON `tbCriterion`.`idtbcriterion` = `tbartifact_has_tbcriterion`.`tbcriterion_idtbcriterion` WHERE `tbartifact_has_tbcriterion`.`tbartifact_idtbartifact` = ".$artifact_id;
				//echo $Sql3."<br>";
				$rs3 = mysql_query($Sql3, $conexao) or die ("Erro na pesquisa-criterio");	
	
				while($linha3 = mysql_fetch_array($rs3))
				{
					$criterion_id=$linha3["tbCriterion_idtbCriterion"];
					$criterion=$linha3["tbCriterionDesc"];
				
					echo "___".$criterion."<br>";
					
					$Sql4 = "SELECT * FROM `tbsubcriterion_has_tbcriterion` INNER JOIN tbsubcriterion ON `tbsubCriterion`.`idtbsubcriterion` = `tbsubcriterion_has_tbcriterion`.`tbsubcriterion_idtbsubcriterion` WHERE `tbsubcriterion_has_tbcriterion`.`tbcriterion_idtbcriterion` = ".$criterion_id;
					//echo $Sql4."<br>";
					$rs4 = mysql_query($Sql4, $conexao) or die ("Erro na pesquisa-subcriterio");	
		
					while($linha4 = mysql_fetch_array($rs4))
					{
						$subcriterion_id=$linha4["tbSubCriterion_idtbSubCriterion"];
						$subcriterion=$linha4["tbSubCriterionDesc"];
					
						echo ".......".$subcriterion."<br>";
					}
					
				}				
				
			}	
			
			echo "<br>";
		}
		
		
		if (isset($_POST['Submit1'])) {
			echo "Relação tipo de Usuário X Artefato";
			$usertype = trim($_POST['sel_usuario']);	
			$artifact = trim($_POST['sel_artifact']);
			
			$Sql = mysql_query("INSERT INTO `tbusertype_has_tbartifact` (`tbUserType_idtbUserType`, `tbArtifact_idtbArtifact`) VALUES ('".$usertype."','".$artifact."')");

			if (!($Sql)) {
					echo "<script language='javascript' type='text/javascript'> alert('Erro!'); </script>";
	
			}
		}

		if (isset($_POST['Submit2'])) {
			echo "Relação Artefato X Critério";
			$artifact = trim($_POST['sel_artifact']);
			$criterion = trim($_POST['sel_criterion']);
			
			$Sql = mysql_query("INSERT INTO `tbartifact_has_tbcriterion` (`tbCriterion_idtbCriterion`, `tbArtifact_idtbArtifact`) VALUES ('".$criterion."','".$artifact."')");

			if (!($Sql)) {
					echo "<script language='javascript' type='text/javascript'> alert('Erro!'); </script>";
	
			}

		
		}

		if (isset($_POST['Submit3'])) {
			echo "Relação Critério X Subcritério";
			$criterion = trim($_POST['sel_criterion']);
			$subcriterion = trim($_POST['sel_subcriterion']);
			
			$Sql = mysql_query("INSERT INTO `tbCriterion_has_tbSubcriterion` (`tbriterion_idtbCriterion`, `tbSubCriterion_idtbSubCriterion`) VALUES ('".$criterion."','".$subcriterion."')");

			if (!($Sql)) {
					echo "<script language='javascript' type='text/javascript'> alert('Erro!'); </script>";
	
			}
		
		
		}

		

?>

<h1>Relações</h1>
<form id="form1" name="form1" method="post" action="insererelacao.php" class="form-horizontal">
<select name="sel_usuario" id="usuario"><?php $Sql = mysql_query("SELECT * FROM `tbusertype`"); while ($rr = mysql_fetch_array($Sql)) { echo "<option value=".$rr['idtbUserType'].">".$rr['tbUserTypeDescripton']."</option>"; } ?></select>
<select name="sel_artifact" id="artifact" class="form-control"><option value="" selected>Artefato</option><?php $Sql = mysql_query("SELECT * FROM `tbArtifact`"); while ($rr = mysql_fetch_array($Sql)) { echo "<option value=".$rr['idtbArtifact'].">".$rr['tbArtifactDescription']."</option>"; } ?></select>
<input type="submit" name="Submit1" value="Inserir" class="btn btn-default"/>
</form>

<form id="form2" name="form2" method="post" action="insererelacao.php" class="form-horizontal">
<select name="sel_artifact" id="artifact" class="form-control"><option value="" selected>Artefato</option><?php $Sql = mysql_query("SELECT * FROM `tbArtifact`"); while ($rr = mysql_fetch_array($Sql)) { echo "<option value=".$rr['idtbArtifact'].">".$rr['tbArtifactDescription']."</option>"; } ?></select>
<select name="sel_criterion" id="criterion"class="form-control"><option value="" selected>Critério</option><?php $Sql = mysql_query("SELECT * FROM `tbCriterion`"); while ($rr = mysql_fetch_array($Sql)) { echo "<option value=".$rr['idtbCriterion'].">".$rr['tbCriterionDesc']."</option>"; } ?></select>
<input type="submit" name="Submit2" value="Inserir" class="btn btn-default"/>
</form>

<form id="form3" name="form3" method="post" action="insererelacao.php" class="form-horizontal">
<select name="sel_criterion" id="criterion"class="form-control"><option value="" selected>Critério</option><?php $Sql = mysql_query("SELECT * FROM `tbCriterion`"); while ($rr = mysql_fetch_array($Sql)) { echo "<option value=".$rr['idtbCriterion'].">".$rr['tbCriterionDesc']."</option>"; } ?></select>
<select name="sel_subcriterion" id="subcriterion"class="form-control"><option value="" selected>Subcritério</option><?php $Sql = mysql_query("SELECT * FROM `tbSubCriterion` ORDER BY `tbSubCriterionDesc`"); while ($rr = mysql_fetch_array($Sql)) { echo "<option value=".$rr['idtbSubCriterion'].">".$rr['tbSubCriterionDesc']."</option>"; } ?></select>
<input type="submit" name="Submit3" value="Inserir" class="btn btn-default"/>
</form>


</body>



</html>