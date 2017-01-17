<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="index2.php">QESWeBS</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="index2.php">Home</a></li>
      <?php 
      	if(isset($_SESSION['user_login'])) {      
      echo '<li class="active"><a href="index2.php">Nova avaliação</a></li>
      <li class="active"><a href="myevaluations.php">Minhas avaliações</a></li>
      <li class="active"><a href="evaluations.php">Outras avaliações</a></li>';
   }
      ?>
      <li><a href="index.php" target="_blank">Saiba mais</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
    	<li><a href="language.php?lang=2">english</a></li>
    	<li><a href="language.php?lang=1">portuguese</a></li>
      <li><a href="#"><span class="glyphicon glyphicon-user"></span>
		 <?php 
      	if (isset($_SESSION['language'])) {
      		if ($_SESSION['language'] == 2) {
					echo 'Welcome ';      		
      		} else {
					echo 'Bem vindo ';      		
      		}
      	} else {
      		echo 'Bem vindo ';	
      	}
      ?>
      <?php 
      	if(isset($_SESSION['user_login'])) {
				echo $_SESSION['user_login'];
			}	      		
      ?>		
      </a></li>
      <li><?php 
      	if(isset($_SESSION['user_login'])) {
      		echo '<a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a>';
      	} else {
				echo  '<a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a>';     	
      	}	
      ?>
     </li>
    </ul>
  </div>
</nav>
