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
        <link rel="stylesheet" href="css/sweetalert.css">
        <script src="js/jquery-3.1.1.min.js"></script>        
        <script src="js/bootstrap.min.js"></script>
        <script src="js/sweetalert.js"></script>
        <link rel="icon" type="image/png" href="favicon.png">
        <title>Avalia SEWebS</title>
    </head>
    <body>
        <div class="container-fluid">
				<?php
            include 'header.php';
            include 'navbar.php';
            
            if ($_SESSION['user_level'] <3) { //Se o nível do usuário não for o esperado, expulsar o mesmo
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
				
				function mostrar_questao ($id) {
					//Mostrar a questão a ser manipulada
					include "conecta.php";
					$Sql = "SELECT * FROM tbquestionid INNER JOIN tbquestiontext ON tbquestionid.idtbQuestionId = tbquestiontext.tbQuestionId_idtbQuestionId WHERE idtbquestionid = ".$id;
					$rs = mysqli_query($conexao, $Sql);
					while($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
						echo "<strong>".$row['idtbQuestionId']."</strong> - ".$row['tbQuestionText']."<br>";
					}
				}
				
				function mostrar_questao2 ($id, $lang) {
					//Mostrar a questão a ser manipulada
					include "conecta.php";
					$Sql = "SELECT * FROM tbquestionid INNER JOIN tbquestiontext ON tbquestionid.idtbQuestionId = tbquestiontext.tbQuestionId_idtbQuestionId WHERE idtbquestionid = ".$id;
					if ($lang == 1) $Sql .= " AND tbLanguage_idtbLanguage = 1";
					if ($lang == 2) $Sql .= " AND tbLanguage_idtbLanguage = 2";
					$rs = mysqli_query($conexao, $Sql);
					
					if (mysqli_num_rows($rs) == 0) return false;
					
					$value[][] = NULL;
					$i=1;
					while($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
						$value[$i][1] = $row['tbQuestionText'];
						$value[$i][2] = $row['tbQuestionTextHowTo'];
						$i++;
					}
					
					return $value;
				}
				
				function mostrar_detalhes ($id) {
					//Mostrar OntologiasFatoresSubfatores já inseridos
					include "conecta.php";
					$Sql = "SELECT * FROM `tbquestion` INNER JOIN tbartifacttext ON tbquestion.tbArtifact_idtbArtifact = tbartifacttext.tbArtifact_idtbArtifact INNER JOIN tbfactortext ON tbquestion.tbFactor_idtbFactor = tbfactortext.tbFactor_idtbFactor INNER JOIN tbsubfactortext ON tbquestion.tbSubFactor_idtbSubFactor = tbsubfactortext.tbSubFactor_idtbSubFactor WHERE tbartifacttext.tbLanguage_idtbLanguage = 1 AND tbfactortext.tbLanguage_idtbLanguage = 1 AND tbsubfactortext.tbLanguage_idtbLanguage = 1 AND tbquestion.tbQuestionId_idtbQuestionId = ".$id;
					$rs = mysqli_query($conexao, $Sql);
					while($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
						
						echo '<form id="form' . $row['idtbQuestion'] . '" name="form' . $row['idtbQuestion'] . '" method="post" action="questioneditor.php" class="form-inline">
								<label>				
								<input type="hidden" size="100" value="' . $row['idtbQuestion'] . '" name="ArtFatSub_id"  style="display:none">
								<input type="hidden" size="100" value="' . $id . '" name="Question_id"  style="display:none">
								<input type="submit" name="SubmitExcludeArtFatSub" value="x" class="btn btn-danger btn-sm"/>
								'.$row['tbArtifactName'].' - '.$row['tbFactorName'].' - '.$row['tbSubFactorName'].'
								</label>								
								</form>
						<br>';
						//echo $row['tbArtifactName'].' - '.$row['tbFactorName'].' - '.$row['tbSubFactorName'];
					}					
				}
				
				function select_artefatos () {
						//Listar artefatos em um select
						include "conecta.php";
							echo '<select name="sel_artifact" id="artifact" class="form-control"><option value="" disabled required selected>Artefato</option>';
							$Sql = "SELECT * FROM tbartifact INNER JOIN tbartifacttext ON tbartifact.idtbArtifact = tbartifacttext.tbArtifact_idtbArtifact WHERE tbartifacttext.tbLanguage_idtbLanguage = 1";
							$rs = mysqli_query($conexao, $Sql);
							while ($row = mysqli_fetch_array($rs, MYSQL_ASSOC)) {
								echo "<option value=".$row['idtbArtifact'].">".$row['tbArtifactName']."</option>";
							}
							echo '</select>';					
				}				
				
				function select_fatores () {
							//Listar fatores em um select
							include "conecta.php";
							echo '<select name="sel_factor" id="factor"class="form-control"><option value="" disabled required selected>Fator</option>';
							$Sql = "SELECT * FROM tbfactor INNER JOIN tbfactortext ON tbfactor.idtbFactor = tbfactortext.tbFactor_idtbFactor WHERE tbfactortext.tbLanguage_idtbLanguage = 1";
							$rs = mysqli_query($conexao, $Sql);
							while ($row = mysqli_fetch_array($rs, MYSQL_ASSOC)) {
								echo "<option value=".$row['idtbFactor'].">".$row['tbFactorName']."</option>";
							}
							echo '</select>';
				}
				
				function select_subfatores () {
					//Listar subfatores em um select
					include "conecta.php";
							echo '<select name="sel_subfactor" id="subfactor"class="form-control"><option value="" disabled required selected>Subfator</option>';
							$Sql = "SELECT * FROM tbsubfactor INNER JOIN tbsubfactortext ON tbsubfactor.idtbsubFactor = tbsubfactortext.tbsubFactor_idtbsubFactor WHERE tbsubfactortext.tbLanguage_idtbLanguage = 1 ORDER BY tbsubfactortext.tbSubFactorName";
							$rs = mysqli_query($conexao, $Sql);
							while ($row = mysqli_fetch_array($rs, MYSQL_ASSOC)) {
								echo "<option value=".$row['idtbSubFactor'].">".$row['tbSubFactorName']."</option>";
							}
							echo '</select><br>';
					
				}
				
				
				
if (isset($_POST['SubmitTextEditor'])) {
            $id = anti_injection($_POST['sel_id']);
            $questionPort = anti_injection($_POST['txt_questionPort']);
            $howtoPort = anti_injection($_POST['howtoPort']);
            $questionEng = anti_injection($_POST['txt_questionEng']);
            $howtoEng = anti_injection($_POST['howtoEng']);
            $english = anti_injection($_POST['english']);
            

				$Sql = "UPDATE `tbquestiontext` SET `tbQuestionText` = '".$questionPort."', `tbQuestionTextHowTo` = '".$howtoPort."' WHERE `tbquestiontext`.`tbLanguage_idtbLanguage` = 1 AND `tbquestiontext`.`tbQuestionId_idtbQuestionId` = ".$id;
				$rs = mysqli_query($conexao, $Sql) or die ("Erro na alteração: português");
				
				if ($english == 1) {
					$Sql = "UPDATE `tbquestiontext` SET `tbQuestionText` = '".$questionEng."', `tbQuestionTextHowTo` = '".$howtoEng."' WHERE `tbquestiontext`.`tbLanguage_idtbLanguage` = 2 AND `tbquestiontext`.`tbQuestionId_idtbQuestionId` = ".$id;
					$rs = mysqli_query($conexao, $Sql) or die ("Erro na alteração: inglês");
				} else {
					$Sql = "INSERT INTO `tbquestiontext` (`tbQuestionText`, `tbQuestionTextHowTo`, `tbLanguage_idtbLanguage`, `tbQuestionId_idtbQuestionId`) VALUES ('".$questionEng."', '".$howtoEng."', '2', '".$id."')";
					$rs = mysqli_query($conexao, $Sql) or die ("Erro na inserção: inglês");
				}
			
			
			
            if ($rs) {
					//Mostrar a questão a ser manipulada
					echo "<h3>".mostrar_questao($id)."</h3>";
					echo '<form id="form1" name="form1" method="post" action="questioneditor.php" class="form-inline">';
					select_artefatos();
					select_fatores();
					select_subfatores();
					echo '<input type="hidden" size="100" value="' . $id . '" name="_id"  style="display:none">';
					echo '<input type="submit" name="SubmitArtFatSub" value="Relacionar Artefato/Fator" class="btn btn-default"/>
							<a class="btn btn-default btn-md " href="questioneditor.php" > Inserir nova questão</a>
							<a class="btn btn-default btn-md " href="questionmanager.php" > Voltar para a lista </a>
							<form><br><p><strong>Artefato - Fator - Subfator</strong><br>';
					mostrar_detalhes($id);
					echo '</p>'; 
  
            } else {
				echo "erowo";
			}

            echo "<p><a href='questionmanager.php'><img src='img/voltar.png' alt='Voltar para lista' height='30'/></a></p>";
            
        } elseif (isset($_GET['id'])) {
        	
        		//Filtrar entradas
        		if (is_numeric($_GET['id'])){
					$id = $_GET['id'];       		
        		}
        		
        		echo 'Questão id: <strong>'.$id.'</strong><br>';
        		mostrar_detalhes($id);
        		echo '<form id="form2" name="form2" method="POST"  class="form-inline" action="questioneditor.php">';
            
            
            
          	echo '<label><h3>Questão em português</h3></label><br>';  
          
          		$texto = mostrar_questao2 ($id, 1);	 
					 echo '<textarea cols="100" rows="3" name="txt_questionPort" class="form-control">'.$texto[1][1].'</textarea><br>
		                <br><label>Texto de ajuda</label><br>
		                <textarea cols="100" rows="3" name="howtoPort" class="form-control">'.$texto[1][2].'</textarea><br>
							 <!-- <input type="submit" name="Submit" form="form2" value="Atualizar" class="btn btn-default" />
		            	 <a class="btn btn-default btn-md " href="questionmanager.php" > Voltar para a lista </a> -->
		            	 <br>';	
            
             echo '<br><br>';
            echo '<label><h3>Questão em inglês</h3></label><br>';
				$texto = mostrar_questao2 ($id, 2);
				          
            if ($texto != false) {
            	$i = 1;
            } else {
            	$i = 0;
            }
				echo '<textarea cols="100" rows="3" name="txt_questionEng" class="form-control">'.$texto[1][1].'</textarea><br>	
            		<input type="hidden" size="100" value="' . $i . '" name="english"  style="display:none">
            		<br><label>Texto de ajuda</label><br>
            		<textarea cols="100" rows="3" name="howtoEng" class="form-control">'.$texto[1][2].'</textarea><br>
            		<input type="hidden" size="100" value="' . $id . '" name="sel_id"  style="display:none">
            		
						<button type="submit" form="form2" value="Submit" name"">Enviar</button>
            		<input type="submit" form="form2" name="SubmitTextEditor" value="Atualizar" class="btn btn-default" />
            		<a class="btn btn-default btn-md " href="questionmanager.php" > Voltar para a lista </a>
            		</form>';
			
			
        } elseif (((isset($_POST['SubmitPort'])) || (isset($_POST['SubmitEng'])))) {
/*
Inserir uma nova questão na tabela de índice e na tabela de texto de acordo com o idioma submetido
*/         	
        		
        		//Filtrar entradas
				$question = anti_injection($_POST['txt_question']);
				$howto = anti_injection($_POST['howto']);	

				//Inserir índice da nova questão	
				$Sql = "INSERT INTO `tbquestionid` (`idtbQuestionId`) VALUES (NULL)";				
				$rs = mysqli_query($conexao, $Sql);
				$id = mysqli_insert_id($conexao);

				if (mysqli_affected_rows($conexao) > 0) {
					if (isset($_POST['SubmitPort'])) $language = 1;
					if (isset($_POST['SubmitEng'])) 	$language = 2;
					$Sql = "INSERT INTO `tbquestiontext` (`tbQuestionText`, `tbQuestionTextHowTo`, `tbLanguage_idtbLanguage`, `tbQuestionId_idtbQuestionId`) VALUES ('".$question."', '".$howto."', '".$language."', '".$id."')";
					$rs = mysqli_query($conexao, $Sql);
					
					if ($rs) {
						echo "<h3>".mostrar_questao($id)."</h3>";
						echo '<form id="form6" name="form6" method="post" action="questioneditor.php" class="form-inline">';
						select_artefatos();
						select_fatores();
						select_subfatores();
						echo '<br>
						<input type="hidden" size="100" value="' . $id . '" name="_id"  style="display:none">
						<input type="submit" name="SubmitArtFatSub" value="Relacionar Artefato/Fator" class="btn btn-default"/>
						<form>';
					}
				} else {
					echo "<script language='javascript' type='text/javascript'> alert('Erro!'); </script>";
				}	
     } elseif (isset($_POST['SubmitArtFatSub'])) {
/*
Inserir na tabela de artefatos, fatores e subfatores relacionados a questão
*/      		
        		//Filtrar entradas
				$id = anti_injection($_POST['_id']);        		
        		
        		
        		if ((isset($_POST['sel_artifact']) & isset($_POST['sel_factor']) & isset($_POST['sel_subfactor']))) {
				$artifact = anti_injection($_POST['sel_artifact']);
				$factor = anti_injection($_POST['sel_factor']);
				$subfactor = anti_injection($_POST['sel_subfactor']);
				

				$Sql = "INSERT INTO `tbquestion` (`idtbQuestion`, `tbArtifact_idtbArtifact`, `tbFactor_idtbFactor`, `tbSubFactor_idtbSubFactor`, `tbQuestionId_idtbQuestionId`) VALUES (NULL, '".$artifact."', '".$factor."', '".$subfactor."', '".$id."')";
				$rs = mysqli_query($conexao, $Sql);
				
				if (!$rs) {
					echo "<script language='javascript' type='text/javascript'> alert('Erro!'); </script>";
				}
				}
					//Mostrar a questão a ser manipulada
					echo "<h3>".mostrar_questao($id)."</h3>";
					echo '<form id="form3" name="form3" method="post" action="questioneditor.php" class="form-inline">';
					select_artefatos();
					select_fatores();
					select_subfatores();
					echo '<input type="hidden" size="100" value="' . $id . '" name="_id"  style="display:none">';
					echo '<input type="submit" name="SubmitArtFatSub" value="Relacionar Artefato/Fator" class="btn btn-default"/>
							<a class="btn btn-default btn-md " href="questioneditor.php" > Inserir nova questão</a>
							<a class="btn btn-default btn-md " href="questionmanager.php" > Voltar para a lista </a>
							<form><br><p><strong>Artefato - Fator - Subfator</strong><br>';
					mostrar_detalhes($id);				
					echo '</p>';
					
		} elseif (isset($_POST['SubmitExcludeArtFatSub'])) {
/*
Inserir na tabela de artefatos, fatores e subfatores relacionados a questão
*/  
			//Filtrar entradas Question_id
				$ArtFatSubid = anti_injection($_POST['ArtFatSub_id']);        		
				$id = anti_injection($_POST['Question_id']); 

				$Sql = "DELETE FROM `tbquestion` WHERE `tbquestion`.`idtbQuestion` = ".$ArtFatSubid;
				$rs = mysqli_query($conexao, $Sql);
				
				if (!$rs) {
					echo "<script language='javascript' type='text/javascript'> alert('Erro!'); </script>";
				}
					//Mostrar a questão a ser manipulada
					echo "<h3>".mostrar_questao($id)."</h3>";
					echo '<form id="form4" name="form4" method="post" action="questioneditor.php" class="form-inline">';
					select_artefatos();
					select_fatores();
					select_subfatores();
					echo '<input type="hidden" size="100" value="' . $id . '" name="_id"  style="display:none">';
					echo '<input type="submit" name="SubmitArtFatSub" value="Relacionar Artefato/Fator" class="btn btn-default"/>
							<a class="btn btn-default btn-md " href="questioneditor.php" > Inserir nova questão</a>
							<a class="btn btn-default btn-md " href="questionmanager.php" > Voltar para a lista </a>
							<form><br><p><strong>Artefato - Fator - Subfator</strong><br>';
					mostrar_detalhes($id);				
					echo '</p>';


			
     } else {
/*
Mostrar o formulário de inserção de nova questão se nenhuma entrada de ambiente for
recebida pela página
*/     	
            echo '<h1>Inserir nova questão</h1>
            		<a class="btn btn-default btn-md " href="questionmanager.php" > Voltar para a lista </a>
            		<form id="form5" name="form5" method="post" action="questioneditor.php" class="form-inline">
     						<label>Questão</label><br>
							<textarea cols="100" rows="3" name="txt_question" class="form-control"></textarea>
							<br>
							<label>Como responder</label><br>
							<textarea cols="100" rows="3" name="howto" class="form-control"></textarea><br>
							<input type="submit" name="SubmitPort" value="Inserir em português" class="btn btn-default"/>
							<!-- <input type="submit" name="SubmitEng" value="Inserir em inglês" class="btn btn-default"/> -->
							<a class="btn btn-default btn-md " href="questionmanager.php" > Voltar para a lista </a>
						</form>';
        }				
				?>
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