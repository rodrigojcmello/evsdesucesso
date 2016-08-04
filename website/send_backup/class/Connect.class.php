<?php
	//CONFIGURAÇÕES DO BANCO
	require_once 'send_backup/inc/config.inc.php';

	class Connect {
		//DATA BASE
		public $db_host = DB_HOST;
		public $db_user = DB_USER;
		public $db_pass = DB_PASS;
		public $db_name = DB_NAME;

		//QUERY SQL
		public $query;

		//CONECTA, SELECIONA e CHARSET
		function conection() {
			$connect = mysql_connect($this->db_host, $this->db_user, $this->db_pass);
			$selectDb = mysql_select_db($this->db_name) or die (mysql_error());
			$charset = mysql_set_charset('UTF8', $connect);

			if($connect && $select) {
				return true;
			} 
			else {
				return false;
			}
		}

		//SELECIONA A PARTIR DA QUERY
		function select() {
			$result = mysql_query($this->query);

			if($result) {
				while ($data = mysql_fetch_array($result)) {
					return $data['usuario_nome'] . "<br>";
				}
			}
			else {
				return "Erro ao inserir no banco de dados";
			}
		}

		//INSERI A PARTIR DA QUERY
		function insert() {
			$result = mysql_query($this->query);

			if($result) {
				return "Dados salvos com sucesso";
			}
			else {
				return "Erro ao salvar no banco de dados";
			}
		}
	}
?>