<?php

include "language.php";

class Crud {
		var $tabela;
		var $campos;
		var $valores;
		var $condicao;
		var $error  = "erro";
		var $sucess = "sucesso";
		var $id;
		var $conn;
		var $bind_param;
		var $bind_param_values;
		private $DB_HOSTNAME = 'localhost';
		private $DB_USERNAME = 'caede741_cida';
		private $DB_PASSWORD = 'caede741_cida';
		private $DB_DATABASE = 'caede741_cida';

		
		function conn(){
			$this->conn = new mysqli($this->DB_HOSTNAME, $this->DB_USERNAME, $this->DB_PASSWORD, $this->DB_DATABASE);
			if(mysqli_connect_errno()) {
				//echo nl2br("Error: Could not connect to database " . mysqli_connect_error() . "\n\n"); //echo + \n need nl2br function
				echo "Erro: Não foi possível conectar com o banco de dados " . mysqli_connect_error();
				exit;
			}
			return $this->conn;
		}
		
		function close() {
			return $this->conn->close();			
		}

		function insert($campos,$tabela,$valores){
			$this->campos  = $campos;
			$this->valores = $valores;
			$this->tabela = $tabela;
			$this->query   = "insert into $this->tabela ($this->campos) values ($this->valores)";
			$conn = $this->conn;
			$query = $conn->prepare($this->query);
			return $query;
		}

		function fastInsert($campos,$tabela,$valores){
			$this->campos  = $campos;
			$this->valores = $valores;
			$this->tabela = $tabela;
			$this->query   = "insert into $this->tabela ($this->campos) values ($this->valores)";
			$conn = $this->conn;
			$w = $conn->query($this->query);
			return $w;
		}

		function update($valores,$tabela,$condicao){
			$this->valores  = $valores;
			$this->condicao = $condicao;
			$this->tabela 	= $tabela;
			$this->query    = "update $this->tabela set $this->valores where $this->condicao";
			$conn = $this->conn;
			$query = $conn->prepare($this->query);
			return $query;
		}

		function selectArrayConditions($campos,$tabela,$condicao){
			$this->campos = implode(",",$campos);
			$this->tabela = $tabela;
			$this->condicao = $condicao;
			$conn = $this->conn;
			$query = $conn->prepare("SELECT $this->campos FROM $this->tabela WHERE $this->condicao");
			return $query;
		}

		function selectArrayPostWhere($campos,$tabela,$condicao){
			$this->campos = $campos;
			$this->tabela = $tabela;
			$this->condicao = $condicao;
			$this->query  = "select $this->campos from $this->tabela $this->condicao";
			$conn = $this->conn;
			$w = $conn->query($this->query);
			return $w;
		}

		function selectCustomQuery($custom){
			echo "ok";
			$this->custom = $custom;
			$conn = $this->conn;
			$w = $conn->query($this->custom);
			return $w;
		}
             
      function getLastID(){
			$conn = $this->conn;
			$w = $conn->insert_id;
			return $w;
		}

		function selectArray($campos){
			$this->campos = $campos;
			$this->query  = "select $this->campos from $this->tabela";
			$w = mysql_query("$this->query");
			return $w;
		}

		function selectDistinct($campos,$tabela,$condicao){
			$this->campos = $campos;
			$this->condicao = $condicao;
			$this->tabela = $tabela;
			$this->query  = "SELECT DISTINCT $this->campos from $this->tabela $this->condicao";
			$conn = $this->conn;
			$w = $conn->query($this->query);
			return $w;
		}

		function delete($tabela,$condicao){
			$this->condicao = $condicao;
			$this->tabela = $tabela;
			$this->query = "delete from $this->tabela where $this->condicao";
			$conn = $this->conn;
			$w = $conn->query($this->query);
			return $w;
		}

		function setMsgSucesso($msg){
			$this->sucess = $msg;
		}

		function setMsgErro($msg){
			$this->error = $msg;
		}

		function real_escape_string($var){
			$this->value = $var;
			$conn = $this->conn;
			$w = $conn->real_escape_string($this->value);
			return $w;
		}
}




class AppOntologies extends Crud {
	var $id;
	var $name;
	var $idApplication;
	
	function AppOntologies (int $idApplication) {
				
	}	
	
	
	function getAppOntologies (int $idApplication) {
	
	
	}

}

class Email {
	var $email;
	var $to;
	var $from;
	private $siteName = 'QualiSWBES';
	
	/**	
	Envia notificação de email para usuário cadastrado em uma avaliação
	**/
	function notifyUser ($email, $user, $from = NULL) {
      $to = $email;
		$subject = "Usuário cadastrado como avaliador";
		$txt = "<html><head><title>HTML email</title></head>
			<body>
			<h3>Olá ".$user."</h3>
			<p>Você foi indicado para avaliar a qualidade de um sistema educacional baseado em web semântica por meio da ferramenta QualiSWBES disponível  <a href='http://avaliasewebs.caed-lab.com/index.php' target='_blank'>aqui</a>.</p>
			<p>Por favor, faça login com seu e-mail e senha cadastrados, acesse a aba AVALIAR no menu superior, para iniciar a avaliação disponibilizada.</p>
			</body>
			</html>";
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= 'From: <no-reply@caed-lab.com>' . "\r\n";
		mail($to,$subject,$txt,$headers);
		return 1;
	}
	
	function notifyNewUser ($email, $user, $from = NULL) {
		$to = $email;
		$subject = "Usuário cadastrado";
		$txt = "<html><head><title>HTML email</title></head>
			<body>
			<h3>Olá ".$user."</h3>
			<p>Você foi cadastrado com sucesso nos sistema de avaliação QualiSWBES. Para saber mais sobre o sistema de avaliação clique <a href='http://avaliasewebs.caed-lab.com/index.php' target='_blank'>aqui</a>.</p>
			<p>Para validar o seu cadastro clique no link abaixo:</p>
			<a href='http://avaliasewebs.caed-lab.com/cadastrar.php?valid=".$valid."' target='_blank'>http://avaliasewebs.caed-lab.com/cadastrar.php?valid=".$valid."</a>
			</body>
			</html>";
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= 'From: <no-reply@caed-lab.com>' . "\r\n";
		
		mail($to,$subject,$txt,$headers);
	}

	function notifyNewPassword ($email, $user, $from = NULL) {
		$to = $email;
		$subject = "Alteração de senha";
		$txt = "<html><head><title>HTML email</title></head>
			<body>
			<h3>Olá ".$user."</h3>
			<p>Foi solicitada a alteração de email para acesso ao sistema de avaliação QualiSWBES. Para saber mais sobre o sistema de avaliação clique <a href='http://avaliasewebs.caed-lab.com/index.php' target='_blank'>aqui</a>.</p>
			<p>Para entrar com a nova senha clique no link abaixo:</p>
			<a href='http://avaliasewebs.caed-lab.com/cadastrar.php?valid=".$valid."' target='_blank'>http://avaliasewebs.caed-lab.com/cadastrar.php?valid=".$valid."</a>
			</body>
			</html>";
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= 'From: <no-reply@caed-lab.com>' . "\r\n";		
		mail($to,$subject,$txt,$headers);
	}


}






?>