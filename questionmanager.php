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
    
    if ($_SESSION['user_level'] <3) { //carregar a página inicial se o usuário não tiver permissão
    	echo "<script> window.location.assign('index.php')</script>";	
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
	

	if (!(isset($_SESSION['start']))) {
		$_SESSION['start'] = 0;		
	} else {
		if (isset($_GET["start"]) AND (is_numeric($_GET["start"]))) {
			$_SESSION['start'] = $_GET["start"];
		} else {
			//$_SESSION['start'] = 0;
		}  
	}
?>	     
	     
	     
	<div class='panel panel-default'>
		<div class='panel-heading'><h1><?php echo $lang['QUESTIONMANAGER_TITLE']; ?></h1></div>
		<div class='panel-body'>
			<h4><a href="questioneditor.php"><img src="img/images.png" height="30" alt="Adicionar nova" /><?php echo $lang['QUESTIONMANAGER_NEW']; ?></a></h4>
	
		
<?php
	$Sql = "SELECT DISTINCT idtbQuestionId, tbQuestionText, tbQuestionTextHowTo, tbquestiontext.tbLanguage_idtbLanguage FROM tbquestionid INNER JOIN tbquestiontext ON tbquestionid.idtbQuestionId = tbquestiontext.tbQuestionId_idtbQuestionId WHERE tbquestiontext.tbLanguage_idtbLanguage = 1 LIMIT ".$_SESSION['start'].", 20";
			//echo $Sql;
	$rs = mysqli_query($conexao, $Sql) or die ("Erro na pesquisa");



	echo '<div style="text-align: right;"><h4>';
	if ($_SESSION['start'] != 0) {
		$newStart = $_SESSION['start']-20;
		echo  "<a href='questionmanager.php?start=".$newStart."'>".$lang['QUESTIONMANAGER_PRIOR']."<span class='glyphicon glyphicon-chevron-left'></span></a>";
	}
	if (!(mysqli_num_rows($rs)<20)) {
		$newStart = $_SESSION['start']+20;
		echo  "<a href='questionmanager.php?start=".$newStart."'><span class='glyphicon glyphicon-chevron-right'></span>".$lang['QUESTIONMANAGER_NEXT']."</a>";
	}
	echo '</h4></div>';

	echo '<table class="table table-condensed table-hover" >
				<tr>
					<td>ID</td>
					<td>'.$lang['QUESTIONMANAGER_QUESTION'].'</td>
					<td>'.$lang['QUESTIONMANAGER_RELATION'].'</td>
				</tr>';
		
	while($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
		$id=$row["idtbQuestionId"];
		echo  '<tr>
					<td>'.$id.'</td>
					<td>'.$row['tbQuestionText'].'<br>';
					mostrar_detalhes($id);
		echo '		<a href="questioneditor.php?id='.$id.'">'.$lang['QUESTIONMANAGER_EDIT'].'</a></td><td><small>
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

	echo '</table>';

	echo '<div style="text-align: right;"><h4>';
	if ($_SESSION['start'] != 0) {
		$newStart = $_SESSION['start']-20;
		echo  "<a href='questionmanager.php?start=".$newStart."'>".$lang['QUESTIONMANAGER_PRIOR']."<span class='glyphicon glyphicon-chevron-left'></span></a>";
	}
	if (!(mysqli_num_rows($rs)<20)) {
		$newStart = $_SESSION['start']+20;
		echo  "<a href='questionmanager.php?start=".$newStart."'><span class='glyphicon glyphicon-chevron-right'></span>".$lang['QUESTIONMANAGER_NEXT']."</a>";
	}
	echo '</h4></div>';
	echo '</div></div>';
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