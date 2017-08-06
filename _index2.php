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
			<script src="slider/angular.min.js"></script>
			<script src="slider/rzslider.min.js"></script>
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
                $user_type = $_POST['usertype'];
                $_SESSION['user_type'] = $user_type;

                if ((isset($_POST['txt_aplic'])) AND ( $_POST['txt_aplic'] != '')) {
                    $aplic = $_POST['txt_aplic'];
                    $aplicDesc = $_POST['txt_aplicdesc'];
                    

							
                    $Sql = "SELECT * FROM tbapplication WHERE tbapplicationname = '" . $aplic . "'";
                    $rs = mysqli_query($conexao, $Sql) or die("Erro busca aplicação");

                    $i = mysqli_num_rows($rs);

                    if ($i) { //Verifica se já existe uma aplicação com o mesmo nome
                        //Trocar confirm() por equivalente em 'SweetAlert'
                        echo '<script> 
								swal({
								  title: "Já existe um sistema cadastrado com esse nome",
								  text: "Escolha o tipo de usuário novamente e selecione um dos sistemas cadastrados e clique em Começar se quiser avaliar esse sistema, ou insira um sistema com outro nome.",
								  type: "warning",
								  showCancelButton: false,
								  timer: 100000,
								  confirmButtonClass: "btn-info",
								  confirmButtonText: "OK",
								  cancelButtonText: "Não",
								  closeOnConfirm: false,
								  closeOnCancel: false
								},
								function(isConfirm) {
								  if (isConfirm) {
								    setTimeout(window.location.assign("index2.php"),100000);
								  } 
								  if (!(isConfirm)) {
								    setTimeout(window.location.assign("index2.php"),100000);
								  } 
								});                        
                        </script>';
								exit();
                        //$linha = mysqli_fetch_array($rs, MYSQLI_ASSOC);
                        //$applic_id = $linha["idtbApplication"];
                        //$_SESSION['appic_id'] = $applic_id;
                    } else {
                    	
                    		//inserir nova aplicação
                        $Sql2 = "INSERT INTO `tbapplication` (`idtbApplication`, `tbApplicationName`, `tbApplicationDescription`) VALUES (NULL, '" . $aplic . "', '" . $aplicDesc . "')";
                        $rs2 = mysqli_query($conexao, $Sql2) or die("Erro insere aplicação");

                        $applic_id = mysqli_insert_id($conexao);
                        $_SESSION['appic_id'] = $applic_id;
                    }
                }

					 //Se a id da aplicação foi recebida via POST, salvar a id na sessão
                if (isset($_POST['sel_aplic'])) {
                    $applic_id = $_POST['sel_aplic'];
                    $_SESSION['appic_id'] = $applic_id;
                }

					 //Verificar se a id de aplicação foi salva na sessão
                if (isset($_SESSION['appic_id'])) {
                	
                    //Procurar no banco um formulário com as mesmas características
                    $Sql = "SELECT * FROM tbform WHERE tbapplication_idtbapplication = '" . $applic_id . "' AND tbuser_idtbUser = '" . $user . "' AND tbUserType_idtbUserType = ".$user_type;
                    $rs = mysqli_query($conexao, $Sql) or die ('Erro na busca de formulário já criado');
                    $linha = mysqli_fetch_array($rs, MYSQLI_ASSOC);
                    


						  //Se não for encontrado nenhum registro de formulário, inserir novo formulário
                    if (mysqli_num_rows($rs) == 0) {
                    		$Sql2 = "INSERT INTO `tbform` (`idtbForm`, `tbApplication_idtbApplication`, `tbUser_idtbUser`, `tbFormCompleted`, `tbFormStatus`, `tbUserType_idtbUserType`, `tbFormDate`) VALUES (NULL, '".$applic_id."', '".$user."', '0', '0', '".$user_type."', NULL)";
                        $rs2 = mysqli_query($conexao, $Sql2) or die ('Erro na inserção do formulário');

                        //obter o id do form inserido
                         $form_id = mysqli_insert_id($conexao);
                         $_SESSION['form_id'] = $form_id;
                         echo "<script> window.location.assign('form.php')</script>";
                         //header('Location:form.php');
                        
                    } else { //Se um registro for encontrado
                    		$linha = mysqli_fetch_array($rs, MYSQLI_ASSOC);
                    		$form_id = $linha["idtbForm"];
                    		$_SESSION['form_id'] = $form_id;
                    		$completed = $linha["tbFormCompleted"];
                    		
                    		echo '<script> 
								swal({
								  title: "Questionário já preenchido",
								  text: "Você já respondeu a um questionário com essa configuração. Indo para resultados...",
								  type: "warning",
								  showCancelButton: false,
								  timer: 100000,
								  confirmButtonClass: "btn-info",
								  confirmButtonText: "OK",
								  cancelButtonText: "Não",
								  closeOnConfirm: false,
								  closeOnCancel: false
								},
								function(isConfirm) {
								  if (isConfirm) {
								    setTimeout(window.location.assign("results.php?form='.$form_id.'"),100000);
								  } 
								  if (!(isConfirm)) {
								    setTimeout(window.location.assign("results.php?form='.$form_id.'"),100000);
								  } 
								});								
								
								</script>';
								exit();
                    }
                }
            }
            ?>

					<div id="passo1" style="text-align: center;" data-toggle="tooltip"><h3>Escolha um dos tipos de avaliadores para começar:</h3></div>
						<div style="text-align: center;">
                        <?php
								$Sql = "SELECT * FROM tbusertypetext WHERE tbLanguage_idtbLanguage = ".$_SESSION['language'];
                			$rs = mysqli_query($conexao, $Sql) or die ("Erro busca");;
            		    	while ($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
       			      		//echo '<div style="text-align: center;">';
            			      echo '<button id=button'.$row["tbUserType_idtbUserType"].' data-toggle="tooltip" data-placement="bottom" title="'.$row['tbUserTypeText'].'" style="text-align: center;" type="submit" class="btn btn-default" onclick="showForm('.$row["tbUserType_idtbUserType"].')" name="submit" value="'.$row["tbUserType_idtbUserType"].'">'.$row["tbUserTypeDesc"].'<br><img src="img/user'.$row["tbUserType_idtbUserType"].'.png" border="0" alt="'.$row["tbUserTypeDesc"].'" height=100px /></button>';
  			      				//echo '</div>';
   			            }                      
	                    	?>
            </div>
				<hr>
				
			<div id="form" style = 'display:none'>
			
				<div style="text-align: center;"><h3> <span class='glyphicon glyphicon-expand'></span> Inicie uma avaliação</h3></div>
            <div style="text-align: center;"><h4>Cadastre um novo sistema educacional baseado em web semântica a ser avaliado:</h4></div>

            <form action="index2.php" class="form-inline" method="post" name="form1" style="text-align: center;">
               <input id="entravalor" class="form-control" name="txt_aplic" placeholder="Nome do sistema" required="required" type="text" />
               <textarea id="" class="form-control" name="txt_aplicdesc" required="required" placeholder="Breve descrição"></textarea>
					<input type="hidden" value="" id="usertype1" name="usertype">
					<input value="Começar" type="submit" class="btn btn-primary btn-lg">

            </form>
            
				<div style="text-align: center;"><h4>ou</h4></div>
            <div style="text-align: center;"><h4>Você também pode avaliar um desses sistemas já cadastrados:</h4></div>

            <form action="index2.php" class="form-inline" method="post" name="form2" style="text-align: center;">
                <p><select id="aplication" class="form-control" name="sel_aplic" placeholder="Nome do sistema">
                        <?php
                        $Sql = "SELECT * FROM tbapplication";
                        $rs = mysqli_query($conexao, $Sql) or die ("Erro busca");
            		    	while ($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
                            echo "<option value=" . $row['idtbApplication'] . ">" . $row['tbApplicationName'] . "</option>";
                        }
                        ?>
                    </select> 
                  <input type="hidden" value="" id="usertype2" name="usertype">
                  <input value="Começar" type="submit" class="btn btn-primary btn-lg">
						</p>
            </form>

            <hr>

			</div>
            

            <?php
            include 'footer.php';
            ?>
        </div>

        <script>
 				/*function show(id) {
				
    				var x = document.getElementById('avaliador'+id);
    				if (x.style.display === 'none') {
        				x.style.display = 'block';
    				}
				}
				
				function hide(id) {
				
    				var x = document.getElementById('avaliador'+id);
    				if (x.style.display === 'block') {
        				x.style.display = 'none';
    				}
				}*/
				
				function showForm(id) {
				
    				var x = document.getElementById('form');
    				if (x.style.display === 'none') {
        				x.style.display = 'block';
    				}
    				
					//esconder todos os botões
					var i;
					for (i=1;i<5;i++) {
						if (!(i==id)) {
							document.getElementById('button'+i).style.display = 'none';
						}						
							
					}
					//document.getElementById('button1').style.display = 'none';
        			//document.getElementById('button2').style.display = 'none';
        			//document.getElementById('button3').style.display = 'none';
        			//document.getElementById('button4').style.display = 'none';
        				
        			//esconder o texto de passo 1	
        			document.getElementById('passo1').style.display = 'none';	
        				
       				
        			//alterar o valor de tipo de usuário no campo escondido nos formulários
        			document.getElementById('usertype1').value = id;	
        			document.getElementById('usertype2').value = id;
						
					document.getElementById('button'+id).style.textAlign = 'center';

				}				         
            
            $(document).ready(function(){
    				$('[data-toggle="tooltip"]').tooltip();
				});
            
        </script>

    </body>

</html>
