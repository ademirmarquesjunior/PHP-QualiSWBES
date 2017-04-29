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
			<!-- <script src="slider/angular.min.js"></script>
			<script src="slider/rzslider.min.js"></script> -->
        <link rel="icon" type="image/png" href="favicon.png">
        <title>Avalia SEWebS</title>
    </head>

    <body>

        <div class="container-fluid">
            <?php
            include 'header.php';
            include 'navbar.php';
            ?>

				<div >
            <?php
            $user = $_SESSION['user_id'];
            
				if (isset($_GET['content']) AND $_GET['content'] == 'manager') {
					
						
					if ($_SESSION['user_level'] >=2) {
						echo "<div class='panel panel-default'>
								<div class='panel-heading'><h1>Gerente de Avaliações</h1></div>
								<div class='panel-body'>";
						
						echo "<h3>Cadastre um novo Sistema a ser avaliado</h3>";
						echo '<form action="evaluationeditor.php" class="form-group" method="post" name="form1" style="text-align: left;">
				               <input id="entravalor" class="form-control" name="txt_aplic" placeholder="Nome do sistema" required="required" type="text" />
				               <textarea id="" class="form-control" name="txt_aplicdesc" required="required" placeholder="Breve descrição"></textarea>
									<input type="hidden" value="" id="usertype1" name="usertype">
									<input value="Inserir" type="submit" class="btn btn-default btn-md">
				
				            </form>';					
						
						echo "<hr><h3>Gerencie avaliações:</h3><p>Insira ou exclua: Avaliadores, objetos de aprendizagem, ontologias e avaliações.</p>";
						$Sql = "SELECT * FROM tbapplication WHERE tbUser_idtbUser = ".$user;
	            	$rs = mysqli_query($conexao, $Sql) or die ("Erro busca");
	            	
	            	if (mysqli_num_rows($rs) == 0) echo "Não há sistemas cadastrados<br>";
	            	
	            	while ($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
	            	   echo "<h4><a href='evaluationeditor.php?applic_id=" . $row['idtbApplication'] . "'><span class='glyphicon glyphicon-zoom-in'></span>  " . $row['tbApplicationName'] . "</a></h4>";
	           		}					
						
					}
					
					echo "</div></div>";
					
				} elseif (isset($_GET['content']) AND $_GET['content'] == 'administrator') {
										
					if ($_SESSION['user_level'] >=3) {
						echo "<div class='panel panel-default'>
								<div class='panel-heading'><h1>Administrador</h1></div>
								<div class='panel-body'>
								<h4><a href='usermanager.php' target='_blank'><span class='glyphicon glyphicon-user'></span> Gerênciar usuários </a></h4>
								<h4><a href='listaquestao.php' target='_blank'><span class='glyphicon glyphicon-question-sign'></span> Gerênciar questões </a></h4>
								<p>Gerencie usuários e suas permissões<br>
								Gerencie os sistemas cadastrados - editar/excluir<br>
								Inserir, editar ou excluir questões<br>
								Alterar o peso para cada questão</p>";
								
								
								
						echo "</div></div>";
					}
				
				} else {
					
	            if ($_SESSION['user_level'] >=1) {
						echo "<div class='panel panel-default'>
								<div class='panel-heading'><h1>Avaliador</h1></div>
								<div class='panel-body'>
								<h3>Veja as avaliações disponíveis para você</h3>
								<img src='img/form.png' height='113' alt=''><br>";
						
						$Sql = "SELECT * FROM `tbform` INNER JOIN tbapplication ON tbform.tbApplication_idtbApplication = tbapplication.idtbApplication INNER JOIN tbusertypetext ON tbform.tbUserType_idtbUserType = tbusertypetext.tbUserType_idtbUserType WHERE tbusertypetext.tbLanguage_idtbLanguage = 1 AND tbFormCompleted = 0 AND tbform.tbUser_idtbUser = ".$user;
						$rs = mysqli_query($conexao, $Sql) or die ("Erro busca");
						if (mysqli_num_rows($rs) == 0) echo "Não há avaliações disponíveis<br>";
	            	
	            	while ($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
	            	   echo "<h4><a href='results.php?form=" . $row['idtbForm'] . "'><span class='glyphicon glyphicon-expand'></span>  " . $row['tbApplicationName'] . " como ".$row['tbUserTypeDesc']."</a></h4>";
	           		}
	           		
	           		echo "<hr><h3>Suas avaliações concluídas</h3>
	           				<img src='img/result.png' height='113' alt=''><br>";
	           		
						$Sql = "SELECT * FROM `tbform` INNER JOIN tbapplication ON tbform.tbApplication_idtbApplication = tbapplication.idtbApplication INNER JOIN tbusertypetext ON tbform.tbUserType_idtbUserType = tbusertypetext.tbUserType_idtbUserType WHERE tbusertypetext.tbLanguage_idtbLanguage = 1 AND tbform.tbFormCompleted = 1 AND tbform.tbUser_idtbUser = ".$user;
						$rs = mysqli_query($conexao, $Sql) or die ("Erro busca");
						if (mysqli_num_rows($rs) == 0) echo "Não há avaliações concluídas<br>";
	            	
	            	while ($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
	            	   echo "<h4><a href='results.php?form=" . $row['idtbForm'] . "'><span class='glyphicon glyphicon-check'></span>  " . $row['tbApplicationName'] . " como ".$row['tbUserTypeDesc']."</a></h4>";
	           		}
	
	           		echo "</div></div>";
					}					
				}	
				?>
				</div>
            <?php
            include 'footer.php';
            ?>
        </div>



    </body>

</html>