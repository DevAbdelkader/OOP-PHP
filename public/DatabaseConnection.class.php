<?php

namespace Database {

	use PDO, Exception;

	class Connection {

		public $connection;
		public $error;

		# PDO DSN
		private const HOST = '127.0.0.1';
		public const PORT = 3306;
		private const DBNAME = 'oop-php';

		# MYSQL User
		private const USER = 'root';
		private const PASS = '';

		# Configuration
		private const CHARSET = 'utf8';
		private const OPTIONS = [
			"MYSQL_ATTR_INIT_COMMAND" => "SET NAMES utf8"
		];


		public function __construct() {
			try {
				$this->connection = new PDO($this->mysql_dsn(), self::USER, self::PASS, self::OPTIONS);
				$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}catch(Exception $e) {
				$this->error = $e->getMessage();
			}
		}

		private function mysql_dsn() {
			return "mysql:host=".self::HOST.";port=".self::PORT.";dbname=".self::DBNAME.";charset=".self::CHARSET;
		}

	}

}



?>