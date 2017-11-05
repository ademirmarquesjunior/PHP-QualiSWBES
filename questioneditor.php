<?php
	session_start();
	include "valida.php";
	include "language.php";
	include "conecta.php";
	include "function.inc.php";
?>
<!DOCTYPE html>
	<html>
		<head>
			<meta content="text/html; charset=utf-8" http-equiv="content-type" />
			<link href="css/bootstrap.min.css" rel="stylesheet">
			<link rel="stylesheet" href="css/sweetalert.css">
			<script src="js/jquery-3.1.1.min.js"></script>        
			<script src="js/bootstrap.min.js"></script>
			<script src="js/sweetalert.js"></script>
			<link rel="icon" type="image/png" href="favicon.png">
			<title><?php echo $lang['PAGE_TITLE']; ?></title>
		</head>
		<body>
			<div class="container-fluid">
<?php
	include 'header.php';
	include 'navbar.php';
?>

			<div class="panel panel-default">
				<div class='panel-heading'><h1><?php echo $lang['QUESTIONEDITOR_TITLE'] ?></h1></div>
				<div class="panel-body">

<?php
	if ($_SESSION['user_level'] <3) { //Se o nível do usuário não for o esperado, enviar o usuário para a página principal
		echo "<script> window.location.assign('index.php')</script>";	
	}

	if (isset($_POST['SubmitTextEditor'])) { //Atualizar a questão e acrescentar a questão em inglês se necessário
		$id = anti_injection($_POST['sel_id']);
		$questionPort = anti_injection($_POST['txt_questionPort']);
		$howtoPort = anti_injection($_POST['howtoPort']);
		$questionEng = anti_injection($_POST['txt_questionEng']);
		$howtoEng = anti_injection($_POST['howtoEng']);


		$Sql = "UPDATE `tbquestiontext` SET `tbQuestionText` = '".$questionPort."', `tbQuestionTextHowTo` = '".$howtoPort."' WHERE `tbquestiontext`.`tbLanguage_idtbLanguage` = 1 AND `tbquestiontext`.`tbQuestionId_idtbQuestionId` = ".$id;
		$rs = mysqli_query($conexao, $Sql) or die ("Erro na alteração: português");

		//Este bloco pode ser feito em apenas um statement sql
		$Sql = "SELECT * FROM `tbquestiontext` WHERE `tbLanguage_idtbLanguage` = 2 AND `tbquestiontext`.`tbQuestionId_idtbQuestionId` = ".$id;
		$rs = mysqli_query($conexao, $Sql) or die ("Erro: inglês");
		if (mysqli_num_rows($rs)!=0) {
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
			echo '	<input type="hidden" size="100" value="' . $id . '" name="_id"  style="display:none">
					<input type="submit" name="SubmitArtFatSub" value="'.$lang['QUESTIONEDITOR_NEW_RELATION'].'" class="btn btn-default"/><br>
				</form>
				<br><strong>'.$lang['QUESTIONEDITOR_LABEL_ARTIFACT-FACTOR'].'</strong><br>';
				mostrar_detalhes($id);
				echo '<hr>
				<a class="btn btn-default btn-md " href="questioneditor.php" >'.$lang['QUESTIONMANAGER_NEW'].'</a>
				<a class="btn btn-default btn-md " href="questionmanager.php" >'.$lang['QUESTIONEDITOR_LIST'].'</a>'; 
			} else {
				echo "erowo";
			}

	} elseif (isset($_GET['id'])) {

		//Filtrar entradas
		if (is_numeric($_GET['id'])) {
			$id = $_GET['id'];       		
		

			echo '<h3>'.$id.'</h3><br>';
			mostrar_detalhes($id);
			echo '<form id="form2" name="form2" method="POST"  class="form-inline" action="questioneditor.php">
				  	<label>'.$lang['QUESTIONEDITOR_LABEL_PORTUGUESE'].'</label><br>';  

			$texto = mostrar_questao2 ($id, 1);	 //português
			echo '	<textarea cols="100" rows="3" name="txt_questionPort" class="form-control">'.$texto[1].'</textarea><br>
					<br><label>'.$lang['QUESTIONEDITOR_LABEL_HELP_TEXT'].'</label><br>
					<textarea cols="100" rows="3" name="howtoPort" class="form-control">'.$texto[2].'</textarea><hr>
					<label>'.$lang['QUESTIONEDITOR_LABEL_ENGLISH'].'</label><br>';

			$texto = mostrar_questao2 ($id, 2); //inglês
			echo '	<textarea cols="100" rows="3" name="txt_questionEng" class="form-control">'.$texto[1].'</textarea><br>
					<br><label>'.$lang['QUESTIONEDITOR_LABEL_HELP_TEXT'].'</label><br>
					<textarea cols="100" rows="3" name="howtoEng" class="form-control">'.$texto[2].'</textarea><br>
					<input type="hidden" size="100" value="' . $id . '" name="sel_id"  style="display:none">
					<hr>
					<input type="submit" form="form2" name="SubmitTextEditor" value="'.$lang['QUESTIONEDITOR_UPDATE'].'" class="btn btn-default" />
					<a class="btn btn-default btn-md " href="questionmanager.php" >'.$lang['QUESTIONEDITOR_LIST'].'</a>
				</form>';

		}

	} elseif (isset($_POST['SubmitNew'])) {
	/*
	Inserir uma nova questão na tabela de índice e na tabela de texto de acordo com o idioma submetido
	*/         	
	    //Filtrar entradas
		$questionPort = anti_injection($_POST['txt_questionPort']);
		$howtoPort = anti_injection($_POST['howtoPort']);	
		$questionEng = anti_injection($_POST['txt_questionEng']);
		$howtoEng = anti_injection($_POST['howtoEng']);	

						//Inserir índice da nova questão	
		$Sql = "INSERT INTO `tbquestionid` (`idtbQuestionId`) VALUES (NULL)";				
		$rs = mysqli_query($conexao, $Sql);
		$id = mysqli_insert_id($conexao);

		if (mysqli_affected_rows($conexao) > 0) {
			$Sql = "INSERT INTO `tbquestiontext` (`tbQuestionText`, `tbQuestionTextHowTo`, `tbLanguage_idtbLanguage`, `tbQuestionId_idtbQuestionId`) VALUES ('".$questionPort."', '".$howtoPort."', '1', '".$id."')";
			$rs = mysqli_query($conexao, $Sql);

			$Sql = "INSERT INTO `tbquestiontext` (`tbQuestionText`, `tbQuestionTextHowTo`, `tbLanguage_idtbLanguage`, `tbQuestionId_idtbQuestionId`) VALUES ('".$questionEng."', '".$howtoEng."', '2', '".$id."')";
			$rs = mysqli_query($conexao, $Sql);

			if ($rs) {
				echo "<h3>".mostrar_questao($id)."</h3>";
				echo '<form id="form6" name="form6" method="post" action="questioneditor.php" class="form-inline">';
				select_artefatos();
				select_fatores();
				select_subfatores();
				echo '<br>
						<input type="hidden" size="100" value="' . $id . '" name="_id"  style="display:none">
						<input type="submit" name="SubmitArtFatSub" value="'.$lang['QUESTIONMANAGER_NEW_RELATION'].'" class="btn btn-default"/>
					  </form>';
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
		echo "<h3>".mostrar_questao($id)."</h3>";
		echo '<form id="form3" name="form3" method="post" action="questioneditor.php" class="form-inline">';
		select_artefatos();
		select_fatores();
		select_subfatores();
		echo '	<input type="hidden" size="100" value="' . $id . '" name="_id"  style="display:none">
				<input type="submit" name="SubmitArtFatSub" value="'.$lang['QUESTIONMANAGER_NEW_RELATION'].'" class="btn btn-default"/>
				<a class="btn btn-default btn-md " href="questioneditor.php" >'.$lang['QUESTIONMANAGER_NEW'].'</a>
				<a class="btn btn-default btn-md " href="questionmanager.php" >'.$lang['QUESTIONEDITOR_LIST'].'</a>
			  </form><br><p><strong>'.$lang['QUESTIONEDITOR_LABEL_ARTIFACT-FACTOR'].'</strong><br>';
		mostrar_detalhes($id);				
		echo '</p>';

	} elseif (isset($_POST['SubmitExcludeArtFatSub'])) {
		/*
		Inserir na tabela de artefatos, fatores e subfatores relacionados a questão
		*/
		$ArtFatSubid = anti_injection($_POST['ArtFatSub_id']);        		
		$id = anti_injection($_POST['Question_id']); 

		$Sql = "DELETE FROM `tbquestion` WHERE `tbquestion`.`idtbQuestion` = ".$ArtFatSubid;
		$rs = mysqli_query($conexao, $Sql);

		if (!$rs) {
			echo "<script language='javascript' type='text/javascript'> alert('Erro!'); </script>";
		}
		echo "<h3>".mostrar_questao($id)."</h3>";
		echo '<form id="form4" name="form4" method="post" action="questioneditor.php" class="form-inline">';
		select_artefatos();
		select_fatores();
		select_subfatores();
		echo '	<input type="hidden" size="100" value="' . $id . '" name="_id"  style="display:none">
				<input type="submit" name="SubmitArtFatSub" value="'.$lang['QUESTIONMANAGER_NEW_RELATION'].'" class="btn btn-default"/>
				<a class="btn btn-default btn-md " href="questioneditor.php" >'.$lang['QUESTIONMANAGER_NEW'].'</a>
				<a class="btn btn-default btn-md " href="questionmanager.php" >'.$lang['QUESTIONEDITOR_LIST'].'</a>
			   </form><br><p><strong>'.$lang['QUESTIONEDITOR_LABEL_ARTIFACT-FACTOR'].'</strong><br>';
		mostrar_detalhes($id);				
		echo '</p>';

	} else {
		/*
		Mostrar o formulário de inserção de nova questão se nenhuma entrada de ambiente for
		recebida pela página
		*/     	
		echo '<h3>'.$lang['QUESTIONEDITOR_INSERT_NEW'].'</h3>
		<form id="form5" name="form5" method="post" action="questioneditor.php" class="form-inline">
			<label>'.$lang['QUESTIONEDITOR_LABEL_PORTUGUESE'].'</label><br>
			<textarea cols="100" rows="3" name="txt_questionPort" class="form-control"></textarea><br>
			<br><label>'.$lang['QUESTIONEDITOR_LABEL_HELP_TEXT'].'</label><br>
			<textarea cols="100" rows="3" name="howtoPort" class="form-control"></textarea><hr>
			<label>'.$lang['QUESTIONEDITOR_LABEL_ENGLISH'].'</label><br>
			<textarea cols="100" rows="3" name="txt_questionEng" class="form-control"></textarea><br>
			<br><label>'.$lang['QUESTIONEDITOR_LABEL_HELP_TEXT'].'</label><br>
			<textarea cols="100" rows="3" name="howtoEng" class="form-control"></textarea><br>
			<hr>
			<input type="submit" name="SubmitNew" value="'.$lang['QUESTIONEDITOR_INSERT_NEW'].'" class="btn btn-default"/>
			<a class="btn btn-default btn-md " href="questionmanager.php" >'.$lang['QUESTIONEDITOR_LIST'].'</a>
		</form>';
	}				
?>
				</div>
			</div>
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