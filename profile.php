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
            ?>


            <?php
            
				if ($_SESSION['user_level'] <1) {
            	echo "<script> window.location.assign('index.php')</script>";	
            }            
            
//----------Funções

         function anti_injection($string) {
				// remove palavras que contenham sintaxe sql
				$string = preg_replace("/(from|FROM|select|SELECT|insert|INSERT|delete|DELETE|where|WHERE|drop table|DROP TABLE|show tables|SHOW TABLES|#|\*|--|\\\\)/","",$string);
				$string = trim($string);//limpa espaços vazio
				$string = strip_tags($string);//tira tags html e php
				$string = addslashes($string);//Adiciona barras invertidas a uma string
				return $string;
			}

        
//-----------------------------------------------------------------------------------------------------------------------------------//
            $user = $_SESSION['user_id'];
            $level = $_SESSION['user_level'];

//----------Salva o perfil do usuário------------------------------------------------------------------------------------------------//
            if (isset($_GET['user'])) {
            	if ($_SESSION['user_level'] >=2) {	
				   	$user_id = anti_injection($_GET['user']);
				   	
						$Sql = "SELECT * FROM `tbuserprofile` INNER JOIN tbuser ON tbuser.idtbUser = tbuserprofile.tbUser_idtbUser WHERE `tbUser_idtbUser` = ".$user_id;
						$rs = mysqli_query($conexao, $Sql);
						
						if(mysqli_num_rows($rs)>0) {
							$row = mysqli_fetch_assoc($rs);				
						
		         
				            echo "<div class='panel panel-default'>
											<div class='panel-heading'><h4>Você está visualizando o perfil de:</h4>
												<div class='panel panel-default'>
												<div class='panel-body'><h3><img src='img/user4.png' border='0' alt='' height=70px />".$row['tbUserName']."</h3></div></div>
											</div>
											<div class='panel-body'>";
								echo "<p><b>Email:</b> ".$row['tbUserEmail'];
								if ($row['tbUserValid'] != 1) { echo ' - <strong>email não validado</strong>'; }
								echo "</p>";								
								
								echo "<p><b>Genero:</b> ";
								if ($row['tbUserProfileGender'] == 1) { echo "masculino."; }
								elseif ($row['tbUserProfileGender'] == 2) { echo "feminino."; }
								else { echo "não informado."; }
								echo "</p>";	
								
								echo "<p><b>Faixa etária:</b> ";
								switch($row['tbUserProfileAge']) {
									case 1:
										echo "até 18 anos.";
										break;
									case 2:
										echo "de 19 a 26 anos.";
										break;
									case 3:
										echo "de 27 a 33 anos.";
										break;
									case 4:
										echo "de 34 a 42 anos.";
										break;
									case 5:
										echo "de 43 a 55 anos.";
										break;
									case 6:
										echo "de 56 a 69.";
										break;
									case 7:
										echo "mais de 70 anos.";
										break;
								}								
								echo "</p>";
								
								echo "<p><b>Escolaridade:</b> ";
								switch($row['tbUserProfileEducation']) {
									case 1:
										echo "Ensino fundamental incompleto.";
										break;
									case 2:
										echo "Ensino fundamental completo.";
										break;
									case 3:
										echo "Ensino médio incompleto.";
										break;
									case 4:
										echo "Ensino médio completo.";
										break;
									case 5:
										echo "Ensino médio técnico.";
										break;
									case 6:
										echo "Ensino superior completo.";
										break;
									case 7:
										echo "Especialização.";
										break;
									case 8:
										echo "Mestrado.";
										break;
									case 9:
										echo "Doutorado.";
										break;
									case 10:
										echo "Pós Doutorado.";
										break;			
								}								
								echo "</p>";	
								
								echo "<p><b>Profissão/Ocupação:</b> ".$row['tbUserProfileOccupation']."</p>";
								echo "<p><b>Empresa/Instituição:</b> ".$row['tbUserProfileInstitution']."</p>";
								echo "<p><b>País:</b> ".$row['tbUserProfileCountry']."</p>";
											
											
								echo "</div></div>";			
															   	
						} else {
							echo "<p>Este usuário ainda não preencheu o seu perfil</p>";
							
						}  
						echo '<p><button type="submit" name="submitNewProfile" class="btn btn-primary" onclick="window.close()">Fechar</button></p>';
					}					   
				}
//----------Salva o perfil do usuário------------------------------------------------------------------------------------------------//
            elseif (isset($_POST['submitNewProfile'])) {
					   $gender = anti_injection($_POST['sel_gender']);
					   $age = anti_injection($_POST['sel_age']);         	
						$educ = anti_injection($_POST['sel_education']);
						$occupation = anti_injection($_POST['txt_occupation']);
						$institution = anti_injection($_POST['txt_institution']);
						$country = anti_injection($_POST['txt_country']);
						$manager = anti_injection($_POST['check_manager']);
						
						$Sql = "SELECT * FROM `tbuserprofile` WHERE `tbUser_idtbUser` = ".$_SESSION['user_id'];
						$rs = mysqli_query($conexao, $Sql);
						
						echo $gender;
						
						if(mysqli_num_rows($rs)) {
							$Sql = "UPDATE `tbuserprofile` SET `tbUserProfileAge` = '".$age."', `tbUserProfileEducation` = '".$educ."', `tbUserProfileGender` = '".$gender."', `tbUserProfileOccupation` = '".$occupation."', `tbUserProfileInstitution` = '".$institution."', `tbUserProfileCountry` = '".$country."' WHERE `tbuserprofile`.`tbUser_idtbUser` = ".$_SESSION['user_id'];
							echo $Sql;
							$rs = mysqli_query($conexao, $Sql);							
							
						} else {
							$Sql = "INSERT INTO `tbuserprofile` (`idtbUserProfile`, `tbUserProfileAge`, `tbUserProfileEducation`, `tbUserProfileGender`, `tbUserProfileOccupation`, `tbUserProfileInstitution`, `tbUserProfileCountry`, `tbUser_idtbUser`) VALUES ('".$_SESSION['user_id']."', '".$age."', '".$educ."', '".$gender."', '".$occupation."', '".$institution."', '".$country."', '".$_SESSION['user_id']."')";
							$rs = mysqli_query($conexao, $Sql);
						}
						
						if ($manager == 2)	{
							echo 'gerente?<br>';
						
						}				
						
            	
            } else { 
            
//----------Formulário para inclusão de ontologias  

				$user = $_SESSION['user_login'];
				$user_id = $_SESSION['user_id']; 
				
				
				$Sql = "SELECT * FROM `tbuserprofile` WHERE `tbUser_idtbUser` = ".$_SESSION['user_id'];
				$rs = mysqli_query($conexao, $Sql);
				
				if(mysqli_num_rows($rs)>0) {
					$row = mysqli_fetch_assoc($rs);
				}
					//echo $row['tbUserProfileOccupation'];
				
				
         
            echo "<div class='panel panel-default'>
							<div class='panel-heading'><h3>Preencha o seu perfil:</h3>
								<div class='panel panel-default'>
								<div class='panel-body'><h1><img src='img/user4.png' border='0' alt='' height=100px />".$user."</h1></div></div>
							</div>
							<div class='panel-body'>";
            
				  
            
            echo '<form action="profile.php" method="post" class="form form-inline">
            	<p>
            	
            		<label>1. Escolha o genero:
            			<select name="sel_gender" class="form-control">
								<option value="1" ';
								if($row['tbUserProfileGender'] == 1) { echo 'selected'; }
								
								echo '>masculino</option>
								<option value="2" ';
								if($row['tbUserProfileGender'] == 2) { echo 'selected'; }
								
								echo '>feminino</option>
								<option value="3" ';
								if($row['tbUserProfileGender'] == 3) { echo 'selected'; }
								
								echo '>prefiro não informar</option>
							</select>
            		</label>&nbsp&nbsp&nbsp&nbsp
            		
						<label>2. Informe a sua faixa etária: 
							<select name="sel_age" class="form-control">
								<option value="1" ';
								if($row['tbUserProfileAge'] == 1) { echo 'selected'; }
								
								echo '>até 18</option>
								<option value="2" ';
								if($row['tbUserProfileAge'] == 2) { echo 'selected'; }
								
								echo '>19 a 26</option>
								<option value="3" ';
								if($row['tbUserProfileAge'] == 3) { echo 'selected'; }
								
								echo '>27 a 33</option>
								<option value="4" ';
								if($row['tbUserProfileAge'] == 4) { echo 'selected'; }
								
								echo '>34 a 42</option>
								<option value="5" ';
								if($row['tbUserProfileAge'] == 5) { echo 'selected'; }
								
								echo '>43 a 55</option>
								<option value="6" ';
								if($row['tbUserProfileAge'] == 6) { echo 'selected'; }
								
								echo '>55 a 69</option>
								<option value="7" ';
								if($row['tbUserProfileAge'] == 7) { echo 'selected'; }
								
								echo '>mais de 70 </option>
							</select>
						</label>&nbsp&nbsp&nbsp&nbsp
						
						<label>3. O seu nível de escolaridade:
							<select name="sel_education" class="form-control">
								<option value="1" ';
								if($row['tbUserProfileEducation'] == 1) { echo 'selected'; }
								
								echo '>Fundamental incompleto</option>
								<option value="2" ';
								if($row['tbUserProfileEducation'] == 2) { echo 'selected'; }
								
								echo '>Fundamental completo</option>
								<option value="3" ';
								if($row['tbUserProfileEducation'] == 3) { echo 'selected'; }
								
								echo '>Ensino médio incompleto</option>
								<option value="4" ';
								if($row['tbUserProfileEducation'] == 4) { echo 'selected'; }
								
								echo '>Ensino médio completo</option>
								<option value="5" ';
								if($row['tbUserProfileEducation'] == 5) { echo 'selected'; }
								
								echo '>Ensino médio técnico</option>
								<option value="6" ';
								if($row['tbUserProfileEducation'] == 6) { echo 'selected'; }
								
								echo '>Ensino superior completo</option>
								<option value="7" ';
								if($row['tbUserProfileEducation'] == 7) { echo 'selected'; }
								
								echo '>Especialização</option>
								<option value="8" ';
								if($row['tbUserProfileEducation'] == 8) { echo 'selected'; }
								
								echo '>Mestrado</option>
								<option value="9" ';
								if($row['tbUserProfileEducation'] == 9) { echo 'selected'; }
								
								echo '>Doutorado</option>
								<option value="10" ';
								if($row['tbUserProfileEducation'] == 10) { echo 'selected'; }
								
								echo '>Pós-Doutorado</option>
							</select>
						</label>            
            	</p>
            	<p>
            		<label>4. Profissão/Ocupação:
            			<input name="txt_occupation" value="'.$row['tbUserProfileOccupation'].'" size="35" type="text" class="form-control" required/>
            		</label>&nbsp&nbsp&nbsp&nbsp
            		<label>5. Instituição/Empresa:
            			<input name="txt_institution" value="'.$row['tbUserProfileInstitution'].'" size="35" type="text" class="form-control" required/>
            		</label>
            	</p>
            	<p>
						<label>6. País:
            			<input name="txt_country" value="'.$row['tbUserProfileCountry'].'" size="35" type="text" class="form-control" required/>
            		</label>
            	</p>
            	<p>
            		<label>7. Deseja se cadastrar como gerente de avaliações? <a href="#" disabled data-toggle="tooltip" data-placement="bottom" title="O usuário Gerente é o gerente de avaliações, cria e configura avaliações de sistemas educacionais baseados em web semântica indicando usuários Avaliadores, além de poder também se colocar como Avaliador. A sua conta de gerente deve ser aprovada por um administrador do sistema.">O que é isto?</a>
							<p><input type="radio" name="check_manager" value="1" checked>  Não</p>
							<p><input type="radio" name="check_manager" value="2">  Sim</p>         		
            		</label>
            	</p>
            	<button type="submit" name="submitNewProfile" class="btn btn-primary">Salvar</button>
            </form>';
            
            echo "</div></div>";
            
				}            
            ?>

            <hr>

<?php
include 'footer.php';
?>
        </div>
        <script>
            $(function(){
    				$('[data-toggle=tooltip]').tooltip();
 				});
        </script>        
        
    </body>
</html>
