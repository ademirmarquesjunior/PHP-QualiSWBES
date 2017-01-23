<?php
session_start();
include "valida.php";
include "language.php";
include "conecta.php";
?>
<!DOCTYPE html>
<html>

    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
        <!-- <link rel="stylesheet" href="estilo.css" type="text/css" media="screen" /> -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <link rel="icon" type="image/png" href="favicon.png">
        <title>Avalia SEWebS</title>
    </head>

    <body>

        <div class="container-fluid">
            <?php
            include 'header.php';
            include 'navbar.php';
            ?>

            <h3>Avaliações concluídas</h3>
            <img src="img/result.png" height="113" alt=""><br>
            
            <h5>Por usuário:</h5>
            
            <?php
            $Sql = "SELECT * FROM tbform INNER JOIN tbapplication ON tbapplication.idtbApplication = tbform.tbApplication_idtbApplication INNER JOIN tbusertypetext ON tbform.tbusertype_idtbUsertype = tbusertypetext.tbusertype_idtbusertype INNER JOIN tbuser ON tbform.tbuser_idtbUser = tbuser.idtbuser WHERE tbform.tbformCompleted = 1 AND tblanguage_idtblanguage = ".$_SESSION['language']." ORDER BY tbApplicationName ";
            $rs = mysqli_query($conexao, $Sql);
            while ($linha = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
                echo "<span class='glyphicon glyphicon-check'></span>".$linha['tbUserName']." avaliou <a href='results.php?form=" . $linha['idtbForm'] . "'>" . $linha['tbApplicationName'] . " [ ".$linha['tbUserTypeDesc']." ]</a><br>";
            }
            if (mysqli_num_rows($rs) == 0)
                echo "Não há avaliações concluídas<br>";
            echo "<p></p>";
            ?>
            
            <hr>
            
				<h5>Por Aplicação:</h5>
				
            <?php
            
            $Sql0 = "SELECT * FROM tbapplication";
				$rs0 = mysqli_query($conexao, $Sql0);
            while ($linha0 = mysqli_fetch_array($rs0, MYSQLI_ASSOC)) {
            	
            	
            	$Sql1 = "SELECT * FROM tbusertype";
					$rs1 = mysqli_query($conexao, $Sql1);
            	while ($linha1 = mysqli_fetch_array($rs1, MYSQLI_ASSOC)) {            
            
            	$Sql = "SELECT * FROM tbform INNER JOIN tbapplication ON tbapplication.idtbApplication = tbform.tbApplication_idtbApplication INNER JOIN tbusertypetext ON tbform.tbusertype_idtbUsertype = tbusertypetext.tbusertype_idtbusertype WHERE tbform.tbformCompleted = 1 AND tblanguage_idtblanguage = ".$_SESSION['language']." AND tbapplication_idtbapplication = ".$linha0['idtbApplication']." AND tbform.tbusertype_idtbusertype = ".$linha1['idtbUserType']." ORDER BY tbApplicationName ";
            	
            	$rs = mysqli_query($conexao, $Sql);

					$count =  mysqli_num_rows($rs); 
					
					           	
					if ($count) {           	
            		$linha = mysqli_fetch_array($rs, MYSQLI_ASSOC);
              		echo "<span class='glyphicon glyphicon-check'></span><a href='results.php?applic=" . $linha['idtbApplication'] . "'>  " . $linha['tbApplicationName'] . " [ ".$linha['tbUserTypeDesc']." ] avaliada por ".$count." avaliadores</a><br>";
            	}
            		}
            		
            	echo "<br>";
         		}
            ?>				
				
				         
            
            <?php
            include 'footer.php';
            ?>
        </div>

    </body>

</html>
