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
		<link href="css/style.css" rel="stylesheet">
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

	if ($_SESSION['user_level'] <1) {
		echo "<script> window.location.assign('index.php')</script>";	
	}

	$user = $_SESSION['user_login'];
	$level = $_SESSION['user_level'];

	if (isset($_GET['user'])) { //Abrir perfil de usuário (por gerente ou administrador)
		if ($_SESSION['user_level'] >=2) {
			$user_id = anti_injection($_GET['user']);

			$Sql = "SELECT * FROM `tbuserprofile` INNER JOIN tbuser ON tbuser.idtbUser = tbuserprofile.tbUser_idtbUser WHERE `tbUser_idtbUser` = ".$user_id;
			$rs = mysqli_query($conexao, $Sql);

			if(mysqli_num_rows($rs)>0) {
				$row = mysqli_fetch_assoc($rs);				

				echo "<div class='panel panel-default'>
						<div class='panel-heading'><h4>".$lang['PROFILE_MESSAGE_VIEWER']."</h4>
							<div class='panel panel-default'>
								<div class='panel-body'><h3><img src='img/user4.png' border='0' alt='' height=70px />".$row['tbUserName']."</h3></div>
							</div>
						</div>
						<div class='panel-body'>
							<p><b>Email:</b> ".$row['tbUserEmail'];
							if ($row['tbUserValid'] != 1) { echo ' - <strong>email não validado</strong>'; }
							echo "</p>
							<p><b>".$lang['PROFILE_GENDER']."</b> ";
							if ($row['tbUserProfileGender'] == 1) { echo $lang['PROFILE_GENDER_MASC']; }
							elseif ($row['tbUserProfileGender'] == 2) { echo $lang['PROFILE_GENDER_FEM']; }
							else { echo $lang['PROFILE_GENDER_NA']; }
							echo "</p>
							<p><b>".$lang['PROFILE_AGE']."</b> ";
							switch($row['tbUserProfileAge']) {
								case 1:
								echo $lang['PROFILE_AGE1'];
								break;
								case 2:
								echo $lang['PROFILE_AGE2'];
								break;
								case 3:
								echo $lang['PROFILE_AGE3'];
								break;
								case 4:
								echo $lang['PROFILE_AGE4'];
								break;
								case 5:
								echo $lang['PROFILE_AGE5'];
								break;
								case 6:
								echo $lang['PROFILE_AGE6'];
								break;
								case 7:
								echo $lang['PROFILE_AGE7'];
								break;
							}								
							echo "</p>
							<p><b>".$lang['PROFILE_EDUCATION']."</b> ";
							switch($row['tbUserProfileEducation']) {
								case 1:
								echo $lang['PROFILE_EDUCATION1'];
								break;
								case 2:
								echo $lang['PROFILE_EDUCATION2'];
								break;
								case 3:
								echo $lang['PROFILE_EDUCATION3'];
								break;
								case 4:
								echo $lang['PROFILE_EDUCATION4'];
								break;
								case 5:
								echo $lang['PROFILE_EDUCATION5'];
								break;
								case 6:
								echo $lang['PROFILE_EDUCATION6'];
								break;
								case 7:
								echo $lang['PROFILE_EDUCATION7'];
								break;
								case 8:
								echo $lang['PROFILE_EDUCATION8'];
								break;
								case 9:
								echo $lang['PROFILE_EDUCATION9'];
								break;
								case 10:
								echo $lang['PROFILE_EDUCATION10'];
								break;			
							}								
							echo "</p>
							<p><b>".$lang['PROFILE_PROFESSION']."</b> ".$row['tbUserProfileOccupation']."</p>
							<p><b>".$lang['PROFILE_INSTITUTION']."</b> ".$row['tbUserProfileInstitution']."</p>
							<p><b>".$lang['PROFILE_COUNTRY']."</b> ".$row['tbUserProfileCountry']."</p>
							</div></div>";			

				} else {
					echo "<p>...</p>"; //Este usuário ainda não preencheu o seu perfil
					
				}  
				echo '<p><button type="submit" name="submitNewProfile" class="btn btn-primary" onclick="window.close()">Fechar</button></p>';
			}					   
		} elseif (isset($_POST['submitNewProfile'])) { //Salvar o perfil do usuário
			$gender = anti_injection($_POST['sel_gender']);
			$age = anti_injection($_POST['sel_age']);         	
			$educ = anti_injection($_POST['sel_education']);
			$occupation = anti_injection($_POST['txt_occupation']);
			$institution = anti_injection($_POST['txt_institution']);
			$country = anti_injection($_POST['txt_country']);
			$manager = anti_injection($_POST['check_manager']);

			$Sql = "SELECT * FROM `tbuserprofile` WHERE `tbUser_idtbUser` = ".$_SESSION['user_id'];
			$rs = mysqli_query($conexao, $Sql);


			if(mysqli_num_rows($rs)) {
				$Sql = "UPDATE `tbuserprofile` SET `tbUserProfileAge` = '".$age."', `tbUserProfileEducation` = '".$educ."', `tbUserProfileGender` = '".$gender."', `tbUserProfileOccupation` = '".$occupation."', `tbUserProfileInstitution` = '".$institution."', `tbUserProfileCountry` = '".$country."' WHERE `tbuserprofile`.`tbUser_idtbUser` = ".$_SESSION['user_id'];
					//echo $Sql;
				$rs = mysqli_query($conexao, $Sql);							

			} else {
				$Sql = "INSERT INTO `tbuserprofile` (`idtbUserProfile`, `tbUserProfileAge`, `tbUserProfileEducation`, `tbUserProfileGender`, `tbUserProfileOccupation`, `tbUserProfileInstitution`, `tbUserProfileCountry`, `tbUser_idtbUser`) VALUES ('".$_SESSION['user_id']."', '".$age."', '".$educ."', '".$gender."', '".$occupation."', '".$institution."', '".$country."', '".$_SESSION['user_id']."')";
				$rs = mysqli_query($conexao, $Sql);
			}

			if ($manager == 1)	{
				$Sql = "UPDATE `tbuser` SET `tbUserManagerRequest` = '1' WHERE `tbuser`.`idtbUser` = ".$_SESSION['user_id'];;
				$rs = mysqli_query($conexao, $Sql);
				//A implantar: mostrar mensagem ou comunicar o administrador						
			}						


			if ($manager == 2)	{
				$Sql = "UPDATE `tbuser` SET `tbUserManagerRequest` = '1' WHERE `tbuser`.`idtbUser` = ".$_SESSION['user_id'];;
				$rs = mysqli_query($conexao, $Sql);
				//A implantar:mostrar mensagem ou comunicar o administrador						
			}				

			echo "<script> swal({title: 'Perfil', text: 'Perfil salvo com sucesso!', type: 'success', showCancelButton: false, confirmButtonClass: 'btn-success', confirmButtonText: 'OK', closeOnConfirm: true}, function() { window.location.assign('index3.php')}); </script>";
			exit();

		} else { //Abrir formulário do perfil
			$Sql = "SELECT * FROM `tbuserprofile` WHERE `tbUser_idtbUser` = ".$_SESSION['user_id'];
			$rs = mysqli_query($conexao, $Sql);

			if(mysqli_num_rows($rs)>0) {
				$row = mysqli_fetch_assoc($rs);
			}

			echo '<div class="panel panel-default">
					<div class="panel-heading"><h3>'.$lang['PROFILE_TITLE'].'</h3>
						<div class="panel panel-default">
							<div class="panel-body"><h1><img src="img/user4.png" border="0" alt="" height=100px />'.$user.'</h1></div>
						</div>
					</div>
					<div class="panel-body">
						<form action="profile.php" method="post" class="form form-inline">
							<p><label>1. '.$lang['PROFILE_GENDER'].':
								<select name="sel_gender" class="form-control">
									<option value="" disabled ';
									if($row['tbUserProfileGender'] == '') { echo 'selected'; }
									echo '>'.$lang['PROFILE_GENDER_SEL'].'</option> 
									<option value="1" ';
									if($row['tbUserProfileGender'] == 1) { echo 'selected'; }
									echo '>'.$lang['PROFILE_GENDER_MASC'].'</option>
									<option value="2" ';
									if($row['tbUserProfileGender'] == 2) { echo 'selected'; }
									echo '>'.$lang['PROFILE_GENDER_FEM'].'</option>
									<option value="3" ';
									if($row['tbUserProfileGender'] == 3) { echo 'selected'; }
									echo '>'.$lang['PROFILE_GENDER_NA'].'</option>
								</select>
							</label>&nbsp&nbsp&nbsp&nbsp
							<label>2. '.$lang['PROFILE_AGE'].' 
								<select name="sel_age" class="form-control">
									<option value="" disabled ';
									if($row['tbUserProfileAge'] == '') { echo 'selected'; }
									echo '>'.$lang['PROFILE_AGE_NONE'].'</option>
									<option value="1" ';
									if($row['tbUserProfileAge'] == 1) { echo 'selected'; }
									echo '>'.$lang['PROFILE_AGE1'].'</option>
									<option value="2" ';
									if($row['tbUserProfileAge'] == 2) { echo 'selected'; }
									echo '>'.$lang['PROFILE_AGE2'].'</option>
									<option value="3" ';
									if($row['tbUserProfileAge'] == 3) { echo 'selected'; }
									echo '>'.$lang['PROFILE_AGE3'].'</option>
									<option value="4" ';
									if($row['tbUserProfileAge'] == 4) { echo 'selected'; }
									echo '>'.$lang['PROFILE_AGE4'].'</option>
									<option value="5" ';
									if($row['tbUserProfileAge'] == 5) { echo 'selected'; }
									echo '>'.$lang['PROFILE_AGE5'].'</option>
									<option value="6" ';
									if($row['tbUserProfileAge'] == 6) { echo 'selected'; }
									echo '>'.$lang['PROFILE_AGE6'].'</option>
									<option value="7" ';
									if($row['tbUserProfileAge'] == 7) { echo 'selected'; }
									echo '>'.$lang['PROFILE_AGE7'].'</option>
								</select>
							</label>&nbsp&nbsp&nbsp&nbsp
							<label>3. '.$lang['PROFILE_EDUCATION'].'
								<select name="sel_education" class="form-control">
									<option value="" disabled ';
									if($row['tbUserProfileEducation'] == '') { echo 'selected'; }
									echo '>'.$lang['PROFILE_EDUCATION0'].'</option>

									<option value="1" ';
									if($row['tbUserProfileEducation'] == 1) { echo 'selected'; }
									echo '>'.$lang['PROFILE_EDUCATION1'].'</option>

									<option value="2" ';
									if($row['tbUserProfileEducation'] == 2) { echo 'selected'; }
									echo '>'.$lang['PROFILE_EDUCATION2'].'</option>

									<option value="3" ';
									if($row['tbUserProfileEducation'] == 3) { echo 'selected'; }
									echo '>'.$lang['PROFILE_EDUCATION3'].'</option>

									<option value="4" ';
									if($row['tbUserProfileEducation'] == 4) { echo 'selected'; }
									echo '>'.$lang['PROFILE_EDUCATION4'].'</option>

									<option value="5" ';
									if($row['tbUserProfileEducation'] == 5) { echo 'selected'; }
									echo '>'.$lang['PROFILE_EDUCATION5'].'</option>

									<option value="6" ';
									if($row['tbUserProfileEducation'] == 6) { echo 'selected'; }
									echo '>'.$lang['PROFILE_EDUCATION6'].'</option>

									<option value="7" ';
									if($row['tbUserProfileEducation'] == 7) { echo 'selected'; }
									echo '>'.$lang['PROFILE_EDUCATION7'].'</option>

									<option value="8" ';
									if($row['tbUserProfileEducation'] == 8) { echo 'selected'; }
									echo '>'.$lang['PROFILE_EDUCATION8'].'</option>

									<option value="9" ';
									if($row['tbUserProfileEducation'] == 9) { echo 'selected'; }
									echo '>'.$lang['PROFILE_EDUCATION9'].'</option>

									<option value="10" ';
									if($row['tbUserProfileEducation'] == 10) { echo 'selected'; }
									echo '>'.$lang['PROFILE_EDUCATION10'].'</option>
								</select>
							</label>            
						</p>
						<p>
							<label>4. '.$lang['PROFILE_PROFESSION'].'
								<input name="txt_occupation" value="'.$row['tbUserProfileOccupation'].'" size="35" type="text" class="form-control" required/>
							</label>&nbsp&nbsp&nbsp&nbsp
							<label>5. '.$lang['PROFILE_INSTITUTION'].'
								<input name="txt_institution" value="'.$row['tbUserProfileInstitution'].'" size="35" type="text" class="form-control" required/>
							</label>
						</p>
						<p>
							<label>6. '.$lang['PROFILE_COUNTRY'].'
								<input name="txt_country" value="'.$row['tbUserProfileCountry'].'" size="35" type="text" class="form-control" required/>
							</label>
						</p>
						<p>
							<label>7. '.$lang['PROFILE_MESSAGE_USER'].' <a href="#" disabled data-toggle="tooltip" data-placement="bottom" title="'.$lang['PROFILE_MESSAGE_USER_TIP'].'">'.$lang['PROFILE_MESSAGE_USER_TIP0'].'</a>
								<p><input type="radio" name="check_manager" value="1" checked>  '.$lang['PROFILE_NO'].'</p>
								<p><input type="radio" name="check_manager" value="2">  '.$lang['PROFILE_YES'].'</p>         		
							</label>
						</p>
						<button type="submit" name="submitNewProfile" class="btn btn-primary">'.$lang['SIGNUP_BTN_SAVE'].'</button>
					</form>';

				echo "</div></div><hr>";
			}           

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
