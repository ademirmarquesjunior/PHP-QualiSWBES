<?php
	session_start();
	include "valida.php";
	include "language.php";
	include "conecta.php";
	include "function.inc.php"
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
?>
            <div class='panel panel-default'>
				<div class='panel-heading'><h3><?php echo $lang['USERMANAGER_TITLE']; ?></h3></div>
				<div class='panel-body'>

<?php
           
    if ($_SESSION['user_level'] <3) {
    	echo "<script> window.location.assign('index.php')</script>";	
    }
    
//----------Formulário para edição de usuário 
    if (isset($_POST['submitEditUser'])) {
    	$user_id = anti_injection($_POST['txt_user_id']);
    	$user = anti_injection($_POST['txt_nome']);
        $email = anti_injection($_POST['txt_email']);
        $user_level = anti_injection($_POST['txt_user_level']);
        
        echo '<h4>'.$lang['USERMANAGER_USER_EDIT'].'</h4>
				<form action="usermanager.php" method="post" name="form1" class="form-group" autocomplete="off">
				<input name="txt_user_id" value="'.$user_id.'" size="12" type="text" hidden/>
				<input maxlength="60" name="txt_nome" id="entravalor2" value="'.$user.'" size="50" class="form-control" required placeholder="'.$lang['USERMANAGER_USER_EDIT_NAME'].'" autocomplete="off"/>
				<input name="txt_email" id="entravalor3" value="'.$email.'" size="40" type="email" class="form-control" required placeholder="Email/login" autocomplete="off"/>';
            echo '<select name="sel_user_level" id="user_type" class="form-control">
		            	<option value="1" ';
		            	if ($user_level == 1) { echo 'selected';}
		            	echo '>'.$lang['USERMANAGER_USER_EDIT_USER1'].'</option>
		            	<option value="2"';
		            	if ($user_level == 2) { echo 'selected';}
		            	echo '>'.$lang['USERMANAGER_USER_EDIT_USER2'].'</option>
		            	<option value="3"';
		            	if ($user_level == 3) { echo 'selected';}
		            	echo '>'.$lang['USERMANAGER_USER_EDIT_USER3'].'</option>			            	
      				</select>';      	
            echo '<input value="'.$lang['SIGNUP_BTN_SAVE'].'" type="submit" class="btn btn-default" name="submitUpdateUser">';
            echo '</form>';
    	
    }
//----------Atualizar usuário            
    elseif (isset($_POST['submitUpdateUser'])) {
    	$user_id = anti_injection($_POST['txt_user_id']);
    	$user = anti_injection($_POST['txt_nome']);
        $email = anti_injection($_POST['txt_email']);
        $user_level = anti_injection($_POST['sel_user_level']);
        
        $Sql = "UPDATE `tbuser` SET `tbUserName` = '".$user."',`tbUserLevel` = '".$user_level."',`tbUserEmail` = '".$email."' WHERE `tbuser`.`idtbUser` = ".$user_id;
        $rs = mysqli_query($conexao, $Sql) or die ("<script language='javascript' type='text/javascript'>swal({   title: '',   text: '".$lang['USERMANAGER_MESSAGE_FAIL2']."',    type: 'error'  },function() { window.location.assign('usermanager.php'); }); </script>");
						
        if (mysqli_affected_rows($conexao)>0) {
        	notifyAlteredUser ($email, $user, $user_level);

            echo "<script language='javascript' type='text/javascript'>
							swal({   title: '',   text: '".$lang['USERMANAGER_MESSAGE_SUCCESS1']."',    type: 'success'  },function() { window.location.assign('usermanager.php'); });
						</script>";
        } else {
            echo "<script language='javascript' type='text/javascript'>
							swal({   title: '',   text: '".$lang['USERMANAGER_MESSAGE_FAIL2']."',    type: 'error'  },function() { window.location.assign('usermanager.php'); });
						</script>";
        }
    }        
    
    
//----------Incluir novo usuário            
    elseif (isset($_POST['submitNewUser'])) {
        $user = anti_injection($_POST['txt_nome']);
        $email = anti_injection($_POST['txt_email']);
        $user_level = anti_injection($_POST['sel_user_type']);
        $password = 0;
        $valid = md5(time());
        $Sql = "INSERT INTO `tbuser` (`idtbUser`, `tbUserName`, `tbUserEmail`, `tbUserPassword`, `tbUserLevel`, `tbUserValid`) VALUES (NULL, '" . $user . "', '" . $email . "', '" . $password . "', '".$user_level."', '" . $valid . "')";
        
        $rs = mysqli_query($conexao, $Sql) or die ("<script language='javascript' type='text/javascript'> swal({   title: '',   text: '".$lang['USERMANAGER_MESSAGE_FAIL1']."',    type: 'error'  },function() { window.location.assign('usermanager.php'); });	</script>");
						
        if (mysqli_affected_rows($conexao)>0) {
        	notifyNewUser($email,$user,$user_level);
        	
            echo "<script language='javascript' type='text/javascript'>
							swal({   title: '',   text: '".$lang['USERMANAGER_MESSAGE_SUCCESS2']."',    type: 'success'  });
						</script>";
        } else {
            echo "<script language='javascript' type='text/javascript'>
					swal({   title: '',   text: '".$lang['USERMANAGER_MESSAGE_FAIL1']."',    type: 'error'  },function() { window.location.assign('usermanager.php'); });
				</script>";
        }
    } else {
        
//----------Exibir lista de usuários            
        
        $Sql = "SELECT * FROM `tbuser` WHERE idtbUser > 1";
			$rs = mysqli_query($conexao, $Sql);
			echo '<table class="table table-bordered table-condensed table-hover">';
			echo '<th>'.$lang['USERMANAGER_USER_EDIT_NAME'].'</th><th>'.$lang['USERMANAGER_USER_TYPE'].'</th><th>Email</th><th>'.$lang['USERMANAGER_USER_VALID'].'</th><th>'.$lang['USERMANAGER_USER_REQUEST'].'</th><th></th>';
			while ($row = mysqli_fetch_assoc($rs)) {
				echo '<form method="post" action="usermanager.php">
							<input name="txt_user_id" value="'.$row['idtbUser'].'" size="12" type="text" hidden/>
							<input name="txt_nome" id="entravalor2" value="'.$row['tbUserName'].'" size="40" type="text" hidden/>
							<input name="txt_email" id="entravalor3" value="'.$row['tbUserEmail'].'" size="40" type="text" hidden/>
							<input name="txt_user_level" id="entravalor5" value="'.$row['tbUserLevel'].'" size="40" type="text" hidden/>';
				echo '<tr>';
				
				echo '<td>'.$row['tbUserName'].'</td>';
				
				echo '<td>';			
				if ($row['tbUserLevel'] == 1) { echo $lang['USERMANAGER_USER_EDIT_USER1']; }
				elseif ($row['tbUserLevel'] == 2) { echo $lang['USERMANAGER_USER_EDIT_USER2']; }
				else { echo $lang['USERMANAGER_USER_EDIT_USER3']; }	
				echo '</td>';
				
				
				echo '<td>'.$row['tbUserEmail'].'</td>';
				echo '<td>';
				if ($row['tbUserValid'] != 1) { echo $lang['PROFILE_NO']; }
				echo '</td>';
				
				echo '<td>';
				if ($row['tbUserManagerRequest'] == 1) { echo $lang['PROFILE_YES']; }
				echo '</td>';						
				
				echo '<td><div style="text-align: right;">
							<button type="submit" class="btn btn-default btn-sm" name="submitEditUser" data-toggle="tooltip" data-placement="bottom" title="'.$lang['USERMANAGER_USER_EDIT'].'!"> <span class="glyphicon glyphicon-pencil"></span> </button>
				<a class="btn btn-info btn-sm" href="profile.php?user='.$row['idtbUser'].'" target="_blank" data-toggle="tooltip" data-placement="bottom" title="'.$lang['PROFILE_MESSAGE_VIEWER'].'"> <span class="glyphicon glyphicon-zoom-in"></span> </a>
				</form></div></td></tr>';
			}
			echo '</table>';
				echo $lang['USERMANAGER_USER_NEW'].'<br>
				<form action="usermanager.php" method="post" name="form1" class="form-inline" autocomplete="off">
					<input maxlength="60" name="txt_nome" id="entravalor2" value="" size="50" class="form-control" required placeholder="'.$lang['USERMANAGER_USER_EDIT_NAME'].'" autocomplete="off"/>
					<input name="txt_email" id="entravalor3" value=" " size="40" type="email" class="form-control" required placeholder="Email/login" autocomplete="off"/>
					<select name="sel_user_type" id="user_type" class="form-control">
			            	<option value="" disabled selected hidden required>'.$lang['USERMANAGER_USER_TYPE'].'</option>
			            	<option value="1">'.$lang['USERMANAGER_USER_EDIT_USER1'].'</option>
			            	<option value="2">'.$lang['USERMANAGER_USER_EDIT_USER2'].'</option>
			            	<option value="3">'.$lang['USERMANAGER_USER_EDIT_USER3'].'</option>			            	
	      			</select>
      				<input value="'.$lang['SIGNUP_BTN_SAVE'].'" type="submit" class="btn btn-default" name="submitNewUser" >
      			</form>';
 	}
    echo '<hr>';

include 'footer.php';
?>
				</div>
			</div>
        </div>
        <script>
            $(function(){
    				$('[data-toggle=tooltip]').tooltip();
 				});
        </script> 
    </body>
</html>
