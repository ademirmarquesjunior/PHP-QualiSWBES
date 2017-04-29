<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <!-- <div class="navbar-header">
      <a class="navbar-brand" href="index3.php">QASWebEd</a>
    </div> -->
    <ul class="nav navbar-nav">
      <li><a href="index.php">Introdução</a></li>
      <?php 
      	if(isset($_SESSION['user_login'])) {      
     			echo '<li><a href="index3.php">Avaliar</a></li>';
      		if ($_SESSION['user_level'] >=2) { echo '<li><a href="index3.php?content=manager">Gerenciar avaliações</a></li>'; }
      		if ($_SESSION['user_level'] >=3) { 
      		echo '<li class="dropdown">
      					<a class="dropdown-toggle" data-toggle="dropdown" href="#">Administrador <span class="caret"></span></a>
							     					
      					<ul class="dropdown-menu">
		          			<li><a href="usermanager.php">Gerenciar usuários</a></li>
		          			<li><a href="questionmanager.php">Gerenciar questões</a></li>
		          		</ul>
      				</li>
      		
      			';
      		}
   		}
      ?>
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
   				<li><a href="cadastrar.php"><span class="glyphicon glyphicon-log-in"></span> Cadastrar</a></li>';
	   }
   ?>
    </ul>
  </div>
</nav>
