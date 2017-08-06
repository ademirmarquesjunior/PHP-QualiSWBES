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
				
				function mostrar_detalhes ($id) {
					//Mostrar OntologiasFatoresSubfatores já inseridos
					include "conecta.php";
					$Sql = "SELECT * FROM `tbquestion` INNER JOIN tbartifacttext ON tbquestion.tbArtifact_idtbArtifact = tbartifacttext.tbArtifact_idtbArtifact INNER JOIN tbfactortext ON tbquestion.tbFactor_idtbFactor = tbfactortext.tbFactor_idtbFactor INNER JOIN tbsubfactortext ON tbquestion.tbSubFactor_idtbSubFactor = tbsubfactortext.tbSubFactor_idtbSubFactor WHERE tbartifacttext.tbLanguage_idtbLanguage = 1 AND tbfactortext.tbLanguage_idtbLanguage = 1 AND tbsubfactortext.tbLanguage_idtbLanguage = 1 AND tbquestion.tbQuestionId_idtbQuestionId = ".$id;
					$rs = mysqli_query($conexao, $Sql);
					while($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
						echo $row['tbArtifactName']." - ".$row['tbFactorName']." - ".$row['tbSubFactorName']."<br>";
					}					
				}
				
//----------Remover uma entrada da lista de questões? x usuarios--------------------------------------------------------------//			
				if (isset($_POST['submitRelationDel'])) {
					$user = anti_injection($_POST['txt_user']);
					$question = anti_injection($_POST['txt_question']);
				
					if ($user != "") {
						$Sql = "DELETE FROM `tbusertype_has_tbuserquestion` WHERE `tbusertype_has_tbuserquestion`.`tbUserType_idtbUserType` = ".$user." AND `tbusertype_has_tbuserquestion`.`tbQuestionId_idtbQuestionId` = ".$question."";
						$rs = mysqli_query($conexao, $Sql) or die ("Erro ao apagar");
					}
				}

//----------Inserir ou editar uma entrada na lista de questões? x Usuários----------------------------------------------------//
				if (isset($_POST['submitRelationAdd'])) {
					$user = anti_injection($_POST['txt_user']);
					$weight = anti_injection($_POST['txt_weight']);
					$question = anti_injection($_POST['txt_question']);
					
					if (!is_numeric($weight)) $weight = 1.00;
				
					if ($user != "") {
						$Sql = "SELECT * FROM tbusertype_has_tbuserquestion WHERE  tbQuestionId_idtbQuestionId = ".$question." AND tbUserType_idtbUserType = ".$user;						
						$rs = mysqli_query($conexao, $Sql) or die ("Erro");											
						if (mysqli_num_rows($rs) == 0) {
							$Sql = "INSERT INTO `tbusertype_has_tbuserquestion` (`tbUserType_idtbUserType`, `tbUserType_has_tbUserQuestionWeight`, `tbQuestionId_idtbQuestionId`) VALUES ('".$user."', '".$weight."', '".$question."')";
							$rs = mysqli_query($conexao, $Sql) or die ("Erro ao inserir");
						} else {
							$Sql = "UPDATE `tbusertype_has_tbuserquestion` SET `tbUserType_has_tbUserQuestionWeight` = '".$weight."' WHERE  `tbusertype_has_tbuserquestion`.`tbUserType_idtbUserType` = ".$user." AND `tbusertype_has_tbuserquestion`.`tbQuestionId_idtbQuestionId` = ".$question;
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
			<a href="questioneditor.php"><img src="img/images.png" height="30" alt="Adicionar nova" />Inserir nova</a>
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

			
			$Sql = "SELECT DISTINCT idtbQuestionId, tbQuestionText, tbQuestionTextHowTo, tbquestiontext.tbLanguage_idtbLanguage FROM tbquestionid INNER JOIN tbquestiontext ON tbquestionid.idtbQuestionId = tbquestiontext.tbQuestionId_idtbQuestionId WHERE tbquestiontext.tbLanguage_idtbLanguage = 1 ";
			//echo $Sql;
			$rs = mysqli_query($conexao, $Sql) or die ("Erro na pesquisa");
			if (mysqli_num_rows($rs) == 0) {
				$_SESSION['artifact'] = '';
				$_SESSION['factor'] = '';
				echo  "<a href='questionmanager.php'>Atualizar</a>";
			}
			
				while($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
					$id=$row["idtbQuestionId"];
					echo  '<tr>
								<td>'.$id.'</td>';
					
					echo "<td><strong>";
					echo $row['tbQuestionText']."</strong><br>";
					mostrar_detalhes($id);
					echo "<a href='questioneditor.php?id=".$id."'>Editar texto</a></td>";
					
					echo '<td>';
					
					echo '<small>
							<table class="table table-condensed table-bordered" style="width:400px">';					
					
					$Sql2 = "SELECT * FROM tbusertype INNER JOIN tbusertypetext ON tbusertype.idtbUserType = tbusertypetext.tbUserType_idtbUserType WHERE tbusertypetext.tbLanguage_idtbLanguage = 1";
					$rs2 = mysqli_query($conexao, $Sql2) or die ("Erro na pesquisa");
					while ($row2 =  mysqli_fetch_array($rs2, MYSQLI_ASSOC)){ 
						echo "<tr><td>".$row2['tbUserTypeDesc']. ":</td> ";
						echo "<td>";
						$Sql3 = "SELECT * FROM tbusertype_has_tbuserquestion WHERE tbusertype_has_tbuserquestion.tbUserType_idtbUserType = ".$row2['idtbUserType']." AND tbusertype_has_tbuserquestion.tbQuestionId_idtbQuestionId = ".$id;
						$rs3 = mysqli_query($conexao, $Sql3) or die ("Erro na pesquisa");
						$row3 =  mysqli_fetch_array($rs3, MYSQLI_ASSOC);
						//echo $row3['tbObjectives_has_tbUserWeight'];
						echo '<form method="post" action="questionmanager.php" class="form form-inline">
									<input name="txt_question" value="'.$id.'" size="12" type="text" hidden/>
									<input name="txt_user" value="'.$row2['idtbUserType'].'" size="12" type="text" hidden/>
									<input name="txt_weight" value="'.$row3['tbUserType_has_tbUserQuestionWeight'].'" size="1" type="text" />
									<button type="submit" name="submitRelationDel" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-minus-sign"></span></button>
									<button type="submit" name="submitRelationAdd" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-plus-sign"></span></button>
								</form>';
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