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


            <?php
            $user = $_SESSION['user_id'];
            //$type = $_SESSION['user_type'];

            if (isset($_POST['txt_aplic']) OR isset($_POST['sel_aplic'])) {
                $applic_id = NULL;
                $user_type = $_POST['submit'];

                if ((isset($_POST['txt_aplic'])) AND ( $_POST['txt_aplic'] != '')) {
                    $aplic = $_POST['txt_aplic'];
                    $aplicDesc = $_POST['txt_aplicdesc'];
                    

							
                    $Sql = "SELECT * FROM tbapplication WHERE tbapplicationname = '" . $aplic . "'";
                    $rs = mysqli_query($conexao, $Sql) or die("Erro busca aplicação");

                    $i = mysqli_num_rows($rs);

                    if ($i) { //Verifica se já existe uma aplicação com o mesmo nome
                        //Trocar confirm() por equivalente em 'SweetAlert'
                        echo "<script language='javascript' type='text/javascript'> if(!confirm('Já existe uma aplicação com esse nome. Deseja avaliar a aplicação encontrada?')) {window.location.href='index2.php'; } </script>";

                        $linha = mysqli_fetch_array($rs, MYSQLI_ASSOC);
                        $applic_id = $linha["idtbApplication"];
                        $_SESSION['appic_id'] = $applic_id;
                    } else {
                    	
                    		//inserir nova aplicação
                        $Sql2 = "INSERT INTO `tbapplication` (`idtbApplication`, `tbApplicationName`, `tbApplicationDescription`) VALUES (NULL, '" . $aplic . "', '" . $aplicDesc . "')";
                        $rs2 = mysqli_query($conexao, $Sql2) or die("Erro insere aplicação");

                        $applic_id = mysqli_insert_id($conexao);
                        $_SESSION['appic_id'] = $applic_id;
                    }
                }


                if (isset($_POST['sel_aplic'])) {
                    $applic_id = $_POST['sel_aplic'];
                    $_SESSION['appic_id'] = $applic_id;
                }


                if (isset($_SESSION['appic_id'])) {
                	
                    //Verificar se há um formulário com as mesmas características
                    $Sql = "SELECT * FROM tbform WHERE tbapplication_idtbapplication = '" . $applic_id . "' AND tbuser_idtbUser = '" . $user . "' AND tbUserType_idtbUserType = ".$user_type;
                    $rs = mysqli_query($conexao, $Sql) or die ('Erro na busca de formulário já criado');
                    $linha = mysqli_fetch_array($rs, MYSQLI_ASSOC);
                    $form_id = $linha["idtbForm"];

                    if (mysqli_num_rows($rs) == 0) { //Inserir novo formulário caso o mesmo não exista
                    		$Sql2 = "INSERT INTO `tbform` (`idtbForm`, `tbApplication_idtbApplication`, `tbUser_idtbUser`, `tbFormCompleted`, `tbFormStatus`, `tbUserType_idtbUserType`, `tbFormDate`) VALUES (NULL, '".$applic_id."', '".$user."', '0', '0', '".$user_type."', NULL)";
                        $rs2 = mysqli_query($conexao, $Sql2) or die('Erro na inserção do formulário');

                        //obter o id do form inserido
                        if ($rs) {
                            $form_id = mysqli_insert_id($conexao);
                            $_SESSION['form_id'] = $form_id;
                            echo "<script> window.location.assign('form.php')</script>";
                            //header('Location:form.php');
                        }
                    } else {
								echo "<script> window.location.assign('results.php?form=".$form_id."')</script>";
                    }
                }
            }
            ?>

				<p>Insira uma novo sistema educacional baseado em web semântica ou escolha um já cadastrado para iniciar uma avaliação, assumindo um dos papéis de avaliador disponíveis. </p>
				
				<?php
                /*$Sql = "SELECT * FROM tbusertypetext WHERE tbLanguage_idtbLanguage = ".$_SESSION['language'];
                $rs = mysqli_query($conexao, $Sql) or die ("Erro busca");;
                while ($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
                   echo "<h4>".$row['tbUserTypeDesc']."</h4>";
                   echo "<p>".$row['tbUserTypeText']."</p>";
                }*/
            ?>
				<hr>
				

            <h3>Cadastrar uma nova aplicação e iniciar avaliação</h3>

            <form action="index2.php" class="form-inline" method="post" name="form1">
                <p><label>Nome da aplicação
                        <input id="entravalor" class="form-control" name="txt_aplic" required="required" type="text" /></label></p>
                <p>
                   <label>Descrição da aplicação<textarea id="" class="form-control" name="txt_aplicdesc" required="required"></textarea></label>
					</p>
					
					<p><label>Escolha um dos tipos de avaliadores para começar:</label></p>
                        <?php
                        
								$Sql = "SELECT * FROM tbusertypetext WHERE tbLanguage_idtbLanguage = ".$_SESSION['language'];
                			$rs = mysqli_query($conexao, $Sql) or die ("Erro busca");;
            		    	while ($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
            			      //echo "<p><label>".$row['tbUserTypeDesc'];
            			      //echo '<input type="image" name="submit'.$row["tbUserType_idtbUserType"].'" value="'.$row["tbUserType_idtbUserType"].'" src="img/user'.$row["tbUserType_idtbUserType"].'.png" border="0" alt="'.$row["tbUserTypeDesc"].'" height=100px />';
            			      echo '<button type="submit" class="btn btn-default" name="submit" value="'.$row["tbUserType_idtbUserType"].'">'.$row["tbUserTypeDesc"].'<img src="img/user'.$row["tbUserType_idtbUserType"].'.png" border="0" alt="'.$row["tbUserTypeDesc"].'" height=100px /></button>';
            			      //echo "</label></p>";
   			            }                      
	                    	?>

            </form>
            <hr>

            <h3>Você também pode avaliar uma dessas aplicações já cadastradas</h3>

            <form action="index2.php" class="form-group" method="post" name="form2">
                <p><label>Nome da aplicação</label></p>
                <p><select id="aplication" class="form-control" name="sel_aplic">
                        <option value="">Escolha uma das opções</option>
                        <?php
                        $Sql = "SELECT * FROM tbapplication";
                        $rs = mysqli_query($conexao, $Sql) or die ("Erro busca");
            		    	while ($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
                            echo "<option value=" . $row['idtbApplication'] . ">" . $row['tbApplicationName'] . "</option>";
                        }
                        ?>
                    </select> </p>
							<p><label>Escolha um dos tipos de avaliadores para começar:</label></p>
                        <?php
                        
								$Sql = "SELECT * FROM tbusertypetext WHERE tbLanguage_idtbLanguage = ".$_SESSION['language'];
                			$rs = mysqli_query($conexao, $Sql) or die ("Erro busca");
            		    	while ($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
            			      //echo "<p><label>".$row['tbUserTypeDesc'];
            			      //echo '<input type="image" name="submit'.$row["tbUserType_idtbUserType"].'" value="'.$row["tbUserType_idtbUserType"].'" src="img/user'.$row["tbUserType_idtbUserType"].'.png" border="0" alt="'.$row["tbUserTypeDesc"].'" height=100px />';
            			      echo '<button type="submit" class="btn btn-default" name="submit" value="'.$row["tbUserType_idtbUserType"].'">'.$row["tbUserTypeDesc"].'<img src="img/user'.$row["tbUserType_idtbUserType"].'.png" border="0" alt="'.$row["tbUserTypeDesc"].'" height=100px /></button>';
            			      //echo "</label></p>";
   			            }                      
	                    	?>                    

            </form>

            <hr>


            <?php
            include 'footer.php';
            ?>
        </div>

    </body>

</html>
