<?php
session_start();
include "valida.php";
include "language.php";
include "conecta.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="content-type" />
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="slider/rzslider.css"/>
        <link rel="stylesheet" href="css/sweetalert.css">
        <script src="js/jquery-3.1.1.min.js"></script>        
        <script src="js/bootstrap.min.js"></script>
        <script src="js/sweetalert.js"></script>
<!-- 			<script src="slider/angular.min.js"></script>
        <script src="slider/rzslider.min.js"></script> -->
        <link rel="icon" type="image/png" href="favicon.png">
        <title>Avalia SEWebS</title>
    </head>
    <body>
        <div class="container-fluid">
				<?php
            include 'header.php';
            include 'navbar.php';
            
            if ($_SESSION['user_level'] <3) {
            	echo "<script> window.location.assign('index.php')</script>";	
            }
            
	         function anti_injection($string) {
					// remove palavras que contenham sintaxe sql
					$string = preg_replace("/(from|FROM|select|SELECT|insert|INSERT|delete|DELETE|where|WHERE|drop table|DROP TABLE|show tables|SHOW TABLES|#|\*|--|\\\\)/","",$string);
					$string = trim($string);//limpa espaços vazio
					$string = strip_tags($string);//tira tags html e php
					$string = addslashes($string);//Adiciona barras invertidas a uma string
					return $string;
				} 
				
//----------Remover uma entrada da lista de objetivos x usuarios--------------------------------------------------------------//			
				if (isset($_POST['submitRelationDel'])) {
					$user = anti_injection($_POST['sel_user']);
					$objective = anti_injection($_POST['sel_objective']);
					$question = anti_injection($_POST['txt_question']);
				
					if (($user != "") AND ($objective != "")) {
						$Sql = "DELETE FROM `tbobjectives_has_tbuserquestion` WHERE `tbObjectives_idtbObjectives` = ".$objective." AND `tbUserQuestion_idtbUserQuestion` = ".$question." AND `tbUserType_idtbUserType` = ".$user.";";
						$rs = mysqli_query($conexao, $Sql) or die ("Erro ao apagar");
					}
				}

//----------Inserir ou editar uma entrada na lista de objetivos x Usuários----------------------------------------------------//
				if (isset($_POST['submitRelationAdd'])) {
					$user = anti_injection($_POST['sel_user']);
					$objective = anti_injection($_POST['sel_objective']);
					$weight = anti_injection($_POST['txt_weight']);
					$question = anti_injection($_POST['txt_question']);
				
					if (($user != "") AND ($objective != "")) {
						$Sql = "SELECT * FROM tbobjectives_has_tbuserquestion WHERE tbObjectives_idtbObjectives = ".$objective." AND tbUserQuestion_idtbUserQuestion = ".$question." AND tbUserType_idtbUserType = ".$user." ";
						$rs = mysqli_query($conexao, $Sql) or die ("Erro");
							
						if (mysqli_num_rows($rs) == 0) {
							$Sql = "INSERT INTO `tbobjectives_has_tbuserquestion` (`idtbObjectives_has_tbUserQuestion`, `tbObjectives_idtbObjectives`, `tbUserQuestion_idtbUserQuestion`, `tbUserType_idtbUserType`, `tbObjectives_has_tbUserWeight`) VALUES (NULL, '".$objective."', '".$question."', '".$user."', '".$weight."')";
							$rs = mysqli_query($conexao, $Sql) or die ("Erro ao inserir");
						} else {
							if (!is_numeric($weight)) $weight = 1.00;
							$Sql = "UPDATE `tbobjectives_has_tbuserquestion` SET `tbObjectives_has_tbUserWeight` = '".$weight."' WHERE `tbobjectives_has_tbuserquestion`.`tbObjectives_idtbObjectives` = ".$objective." AND `tbobjectives_has_tbuserquestion`.`tbUserQuestion_idtbUserQuestion` = ".$question." AND `tbobjectives_has_tbuserquestion`.`tbUserType_idtbUserType` = ".$user.";";
							$rs = mysqli_query($conexao, $Sql) or die ("Erro ao editar");
						}
					}
				}				
				
			$list = " ";
			if (isset($_POST["submitlistar"])) {
				$_SESSION['artifact'] = anti_injection($_POST['sel_artifact']);
				$_SESSION['factor'] = anti_injection($_POST['sel_Factor']);
			} 
			if (isset($_SESSION['artifact']) AND ($_SESSION['artifact'] != ""))  $list = $list." AND tbartifacttext.tbartifact_idtbartifact = ".$_SESSION['artifact'];
			if (isset($_SESSION['artifact']) AND ($_SESSION['factor'] != "")) $list = $list." AND tbfactortext.tbFactor_idtbFactor = ".$_SESSION['factor'];
				           
            ?>			     
			     
			     
			<div class='panel panel-default'>
			<div class='panel-heading'><h1>Gerenciamento de questões</h1></div>
			<div class='panel-body'>
			<p>
			<a href="inserequestao.php"><img src="img/images.png" height="30" alt="Adicionar nova" />Inserir nova</a>
			</p>
			
			<form id="form2" name="form2" method="post" action="questionmanager.php" class="form-inline">
			<select name="sel_artifact" id="artifact" class="form-control"><option value="" selected>Artefato</option>
			<?php
			$Sql = "SELECT * FROM tbartifacttext WHERE tblanguage_idtblanguage = 1";
			$rs = mysqli_query($conexao, $Sql);
			while ($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
			echo "<option value=".$row['tbArtifact_idtbArtifact'].">".$row['tbArtifactName']."</option>";
			} ?></select>
			
			<select name="sel_Factor" id="Factor"class="form-control"><option value="" selected>Fator</option>
			<?php 
			$Sql = "SELECT * FROM `tbfactortext` WHERE tblanguage_idtblanguage = 1";
			$rs = mysqli_query($conexao, $Sql);
			while ($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
			echo "<option value=".$row['tbFactor_idtbFactor'].">".$row['tbFactorName']."</option>";
			} ?></select>
			<input type="submit" name="submitlistar" value="Listar" class="btn btn-default"/>
			</form>
			
			
			<table class="table table-condensed table-hover" >
				<tr>
					<td>ID</td>
					<td>Questão</td>
					<td>Relacionamento</td>
				</tr>	
			
			<?php

			
			$Sql = "SELECT DISTINCT idtbUserQuestion, tbuserquestion.tbArtifact_idtbArtifact, tbArtifactName, tbfactortext.tbFactorName, tbSubFactorName,  tbQuestionText,  tbQuestionTextHowTo,  tbquestiontext.tbLanguage_idtbLanguage FROM  tbuserquestion INNER JOIN  tbquestiontext ON tbuserquestion.idtbUserQuestion = tbquestiontext.tbUserQuestion_idtbUserQuestion INNER JOIN  tbobjectives_has_tbuserquestion ON tbuserquestion.idtbUserQuestion = tbobjectives_has_tbuserquestion.tbUserQuestion_idtbUserQuestion INNER JOIN tbartifacttext ON tbuserquestion.tbartifact_idtbArtifact = tbartifacttext.tbArtifact_idtbArtifact INNER JOIN tbfactortext ON tbuserquestion.tbFactor_idtbFactor = tbfactortext.tbFactor_idtbFactor INNER JOIN tbsubfactortext ON tbuserquestion.tbSubFactor_idtbSubFactor = tbsubfactortext.tbSubFactor_idtbSubFactor WHERE tbquestiontext.tbLanguage_idtbLanguage = 1 AND tbartifacttext.tbLanguage_idtbLanguage = 1 AND tbfactortext.tbLanguage_idtbLanguage = 1 AND tbsubfactortext.tbLanguage_idtbLanguage = 1 ". $list." ORDER BY tbartifacttext.tbArtifactName, tbfactortext.tbFactorName, tbsubfactortext.tbSubFactorName";
			$rs = mysqli_query($conexao, $Sql) or die ("Erro na pesquisa");
			if (mysqli_num_rows($rs) == 0) {
				$_SESSION['artifact'] = '';
				$_SESSION['factor'] = '';
				echo  "<script type='text/javascript'> window.location.assign('questionmanager.php'); </script>";
			}
			
				while($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
					$id=$row["idtbUserQuestion"];
					echo  '<tr>
								<td>'.$id.'</td>';
					
					echo "<td>".$row['tbArtifactName']." , ".$row['tbFactorName']." , ".$row['tbSubFactorName']."<br><strong>".$row['tbQuestionText']."</strong><br><a href='editaquestao.php?id=".$id."'>Editar texto</a></td>";
					
					echo '<td>
								<form method="post" action="questionmanager.php" class="form form-inline">';
									$Sql2 = "SELECT * FROM tbusertype INNER JOIN tbusertypetext ON tbusertype.idtbUserType = tbusertypetext.tbUserType_idtbUserType WHERE tbusertypetext.tbLanguage_idtbLanguage = 1";
									echo '<select name="sel_user" id="user" class="form-control" style="width: 35%">';
									echo '<option value="">Avaliador</option>';
									$rs2 = mysqli_query($conexao, $Sql2) or die ("Erro na pesquisa");
									while ($row2 =  mysqli_fetch_array($rs2, MYSQLI_ASSOC)){ 
										echo "<option value=" . $row2['idtbUserType'] . ">" . $row2['tbUserTypeDesc'] . "</option>";
									}
									echo '</select>';
					
									echo '<input name="txt_question" value="'.$id.'" size="12" type="text" hidden/>';
					
									$Sql2 = "SELECT * FROM tbobjectives INNER JOIN tbobjectivestext ON tbobjectives.idtbObjectives = tbobjectivestext.tbObjectives_idtbObjectives WHERE tbobjectivestext.tbLanguage_idtbLanguage = 1";
									echo '<select name="sel_objective" id="objective" class="form-control" style="width: 35%">
										<option value="">Objetivo</option>';
										$rs2 = mysqli_query($conexao, $Sql2) or die ("Erro na pesquisa");
										while ($row2 =  mysqli_fetch_array($rs2, MYSQLI_ASSOC)){ 
											echo "<option value=".$row2['idtbObjectives'].">".$row2['tbObjectivesDesc']."</option>";
										}
									echo '</select>';
					
									echo '<input name="txt_weight" value="1.00" size="1" type="text" class="form-control"/>
									<button type="submit" name="submitRelationDel" class="btn btn-danger"><span class="glyphicon glyphicon-minus-sign"></span></button>
									<button type="submit" name="submitRelationAdd" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span></button>
								</form>';
					
					echo '<small>
							<table class="table table-condensed table-bordered">
							<tr><td></td>';
							
					$Sql2 = "SELECT * FROM tbobjectives INNER JOIN tbobjectivestext ON tbobjectives.idtbobjectives = tbobjectivestext.tbobjectives_idtbobjectives WHERE tbobjectivestext.tbLanguage_idtbLanguage = 1";
					$rs2 = mysqli_query($conexao, $Sql2) or die ("Erro na pesquisa");
					$objectives = mysqli_num_rows($rs2);
					while ($row2 =  mysqli_fetch_array($rs2, MYSQLI_ASSOC)){
						echo "<td>".$row2['tbObjectivesDesc']."</td>";
					}
					echo "</tr>";
					
					
					$Sql2 = "SELECT * FROM tbusertype INNER JOIN tbusertypetext ON tbusertype.idtbUserType = tbusertypetext.tbUserType_idtbUserType WHERE tbusertypetext.tbLanguage_idtbLanguage = 1";
					$rs2 = mysqli_query($conexao, $Sql2) or die ("Erro na pesquisa");
					while ($row2 =  mysqli_fetch_array($rs2, MYSQLI_ASSOC)){ 
						echo "<tr><td>".$row2['tbUserTypeDesc']. ":</td> ";
						
						for($i=1;$i<$objectives+1;$i++) {
							echo "<td>";
							$Sql3 = "SELECT * FROM tbobjectives_has_tbuserquestion WHERE tbobjectives_has_tbuserquestion.tbUserType_idtbUserType = ".$row2['idtbUserType']." AND tbobjectives_has_tbuserquestion.tbUserQuestion_idtbUserQuestion = ".$id." AND tbobjectives_has_tbuserquestion.tbobjectives_idtbobjectives = ".$i;
							//echo $Sql3;
							$rs3 = mysqli_query($conexao, $Sql3) or die ("Erro na pesquisa");
							$row3 =  mysqli_fetch_array($rs3, MYSQLI_ASSOC);
								//echo $row3['tbObjectivesDesc']."(".$row3['tbObjectives_has_tbUserWeight'].");";
							echo $row3['tbObjectives_has_tbUserWeight'];
							echo "</td>";
						}
						echo "</tr>";
					}
					echo '</table></small>';			
					echo "</td>";
					echo  "</tr>";
					}
			?>
			</table>
			</div></div>
<?php
include 'footer.php';
?>
        </div>
        <script>

            $(function(){
    				$('[data-toggle=tooltip]').tooltip();
 				});
        </script>        
        
		</div>	
</body>

</html>