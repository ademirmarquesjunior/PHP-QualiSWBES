<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="estilo.css" type="text/css" media="screen" />
<title></title>
</head>
<body>

<h1>Login de usuário</h1>


<div id="login">
<?php
//include("valida.php");
echo 'Bem vindo visitante';
?>
</div>


<?php
session_start();
if(isset($_SESSION['user_login'])) {
echo "Bem vindo '".$_SESSION['user_login']."' ";
echo "<a href='logout.php'>Sair</a>";
exit();
} 
?>
Clique <a href="cadastrar.php">aqui</a> para se cadastrar e ter acesso ao sistema.

<form action="login.php" method="post">
Email:
<input name="txt_usuario" type="text" id="entravalor"  size="13" />
Senha:
<input name="txt_senha" type="password" id="entravalor"  size="13" maxlength="13"  />
<input type="submit" value="Login"/>
</form>

<?php

echo "</ br> Faça o seu login para ter acesso ao sistema";

	include "conecta.php";
	
	if (isset($_POST['txt_usuario'])) {
		$usuario = $_POST['txt_usuario']; 
	} else {
		exit;
	}
	if (isset($_POST['txt_senha'])) $senha = md5($_POST['txt_senha']);
	

		$sql='select * from tbUsuario where tbUsuarioLogin="'.$usuario.'" and tbUsuarioSenha="'.$senha.'"';	
		$rs=mysql_query($sql, $conexao) or die ("Usuário ou senha incorreta");	
		
		while($linha = mysql_fetch_array($rs))
			{
			$idtbUsuario=$linha["idtbUsuario"];
			$nome=$linha["tbUsuarioNome"];
			$nivel=$linha["tbPermissao_idtbPermissao"];
			}
			
			echo $nome;
			
			if ($nome!=false) {		
			$_SESSION['user_login'] = $nome;
			$_SESSION['cod_usuario']=$idtbUsuario;
			$_SESSION['permissao']=$nivel;
			mysql_close($conexao);
			header('Location:index.php');
			
			}
			
?>
<body>

</body>
</html>