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
        <meta name="description" content="Abordagem para avaliação da qualidade de sistemas educacionais baseados em Web Semântica, Approach for quality evaluation of educational systems based on Semantic Web">
        <meta name="keywords" content="Web Semântica, educação, qualidade, avaliação, abordagem, Semantic Web, educational, quality, assesment, approach, education, USP, ICMC, SWBES">
        <meta name="author" content="Aparecida Maria Zem Lopes, Ademir Marques Junior, Seiji Isotani">
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

	$user = $_SESSION['user_id'];

	if (isset($_GET['content']) AND $_GET['content'] == 'manager') { //Mostrar o conteúdo para o usuário gerente de avaliações
		if ($_SESSION['user_level'] >=2) {
			echo '<div class="panel panel-default">
					<div class="panel-heading"><h1>'.$lang['INDEX_MANAGER'].'</h1></div>
					<div class="panel-body">
						<h3>'.$lang['INDEX_MANAGER_NEW'].'</h3>
						<form action="evaluationeditor.php" class="form-group" method="post" name="form1" style="text-align: left;">
							<input id="entravalor" class="form-control" name="txt_aplic" placeholder="'.$lang['INDEX_MANAGER_PLACEHOLDER_NAME'].'" required="required" type="text" />
							<input type="hidden" value="" id="usertype1" name="usertype">
							<input value="'.$lang['INDEX_MANAGER_BUTTON'].'" type="submit" class="btn btn-default btn-md">
			            </form><hr>
			          	<h3>'.$lang['INDEX_MANAGER'].'</h3><p>'.$lang['INDEX_MANAGER_MESSAGE'].'</p>';

			$Sql = "SELECT * FROM tbapplication WHERE tbUser_idtbUser = ".$user;
    		$rs = mysqli_query($conexao, $Sql) or die ("Erro busca");
    	
    		if (mysqli_num_rows($rs) == 0) echo $lang['INDEX_MANAGER_NONE'].'<br>';
    	
			while ($row = mysqli_fetch_assoc($rs)) {
			   echo "<h4><a href='evaluationeditor.php?applic_id=" . $row['idtbApplication'] . "'><span class='glyphicon glyphicon-zoom-in'></span>  " . $row['tbApplicationName'] . "</a></h4>";
				}
   		
	   		if ($_SESSION['user_level'] >=3) { //Se o usuário for administrador, mostrar os sistemas gerenciados por outros usuários
	       		echo '<br><br><p>'.$lang['INDEX_MANAGER_ADM'].':</p>';
	       		$Sql = "SELECT * FROM tbapplication INNER JOIN tbuser ON tbuser.idtbUser = tbapplication.tbUser_idtbUser WHERE tbUser_idtbUser != ".$user;
	        	$rs = mysqli_query($conexao, $Sql) or die ("Erro busca");
	        	
        		if (mysqli_num_rows($rs) == 0) echo $lang['INDEX_MANAGER_NONE'].'<br>';
        	
	        	while ($row = mysqli_fetch_assoc($rs)) {
	        	   echo "<h4><a href='evaluationeditor.php?applic_id=" . $row['idtbApplication'] . "'><span class='glyphicon glyphicon-zoom-in'></span>  " . $row['tbApplicationName'] . " por " . $row['tbUserName'] . "</a></h4>";
	       		}	
	   		}					
			
		}
		
		echo '</div></div>';
			
	} else { //Se o usuário não tiver permissões especiais mostrar os sistemas disponíveis para o mesmo avaliar
		
	    if ($_SESSION['user_level'] >=1) {
			echo '<div class="panel panel-default">
					<div class="panel-heading">
						<h1>'.$lang['INDEX_USER'].'</h1>
					</div>
					<div class="panel-body">
						<h3>'.$lang['INDEX_USER_LIST'].'</h3>
						<img src="img/form.png" height="113" alt=""><br>';
				
				$Sql = "SELECT * FROM `tbform` INNER JOIN tbapplication ON tbform.tbApplication_idtbApplication = tbapplication.idtbApplication INNER JOIN tbusertypetext ON tbform.tbUserType_idtbUserType = tbusertypetext.tbUserType_idtbUserType WHERE tbusertypetext.tbLanguage_idtbLanguage = 1 AND tbFormCompleted = 0 AND tbform.tbUser_idtbUser = ".$user;
				$rs = mysqli_query($conexao, $Sql) or die ("Erro busca");
				if (mysqli_num_rows($rs) == 0) echo $lang['INDEX_USER_LIST_NONE'].'<br>';
	    	
	    	while ($row = mysqli_fetch_assoc($rs)) {
	    	   echo "<h4><a href='results.php?form=" . $row['idtbForm'] . "'><span class='glyphicon glyphicon-expand'></span>  " . $row['tbApplicationName'] . " como ".$row['tbUserTypeDesc']."</a></h4>";
	   		}
	   		
	   		echo '<hr><h3>'.$lang['INDEX_USER_LIST_FINISHED'].'</h3>
	   				<img src="img/result.png" height="113" alt=""><br>';
	   		
				$Sql = "SELECT * FROM `tbform` INNER JOIN tbapplication ON tbform.tbApplication_idtbApplication = tbapplication.idtbApplication INNER JOIN tbusertypetext ON tbform.tbUserType_idtbUserType = tbusertypetext.tbUserType_idtbUserType WHERE tbusertypetext.tbLanguage_idtbLanguage = 1 AND tbform.tbFormCompleted = 1 AND tbform.tbUser_idtbUser = ".$user;
				$rs = mysqli_query($conexao, $Sql) or die ("Erro busca");
				if (mysqli_num_rows($rs) == 0) echo $lang['INDEX_USER_LIST_NONE'].'<br>';
	    	
	    	while ($row = mysqli_fetch_assoc($rs)) {
	    	   echo "<h4><span class='glyphicon glyphicon-check'></span>  " . $row['tbApplicationName'] . " como ".$row['tbUserTypeDesc']."</h4>";
	   		}

	   		echo "</div></div>";
		}					
	}	
?>
<?php
include 'footer.php';
?>
        </div>
    </body>
</html>