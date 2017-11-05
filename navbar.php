<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <!-- <div class="navbar-header">
      <a class="navbar-brand" href="index.php"><?php echo $lang['SITE_NAME']; ?></a>
    </div> -->
    <ul class="nav navbar-nav">
      <li><a href="index.php"><?php echo $lang['MENU_INTRODUCTION']; ?></a></li>
<?php 
	if(isset($_SESSION['user_login'])) {      
			echo '<li><a href="index3.php">'.$lang['MENU_EVALUATE'].'</a></li>';
		if ($_SESSION['user_level'] >=2) { echo '<li><a href="index3.php?content=manager">'.$lang['MENU_MANAGER'].'</a></li>'; }
		if ($_SESSION['user_level'] >=3) { 
		echo '<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">'.$lang["MENU_ADMINISTRATOR"].' <span class="caret"></span></a>
				     					
					<ul class="dropdown-menu">
        			<li><a href="usermanager.php">'.$lang['MENU_ADM_USERS'].'</a></li>
        			<li><a href="questionmanager.php">'.$lang['MENU_ADM_QUESTIONS'].'</a></li>
        		</ul>
				</li>';
		}
	}
?>
<li><a href="about.php"><?php echo $lang['MENU_ABOUT']; ?></a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
<?php 
  if(isset($_SESSION['user_login'])) {
  	echo '<li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
				'.$_SESSION['user_login'].'	      		
      	<span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="profile.php"><span class="glyphicon glyphicon-user"></span> Perfil</a></li>
          <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
      </li>';
 } else {
		echo '<li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
				<li><a href="cadastrar.php"><span class="glyphicon glyphicon-log-in"></span> '.$lang['MENU_SIGNUP'].'</a></li>';
 }
?>
    </ul>
  </div>
</nav>
