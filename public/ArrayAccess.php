<?php

	// =================================
	//	---------------
	//	- ArrayAccess -
	//	---------------
	//
	//	Notes
	//	=====
	//	- No Data Validation\Sanitizing
	// =================================




	// Strict mode enabled

	ini_set("display_errors", 1);
	ini_set("display_startup_errors", 1);
	error_reporting(E_ALL);




	include 'DatabaseConnection.class.php';


	use Database\Connection;


	class UserCollection implements ArrayAccess {

		// Some code are implemented from PHP Notes For Professionals book

		private $_conn;

		private $_requiredParams = [
			'username',
			'first_name',
			'email',
			'password'
		];



		public function __construct() {
			$this->_conn = new Connection();
			$this->_conn = $this->_conn->connection;
		}



		public function _getByEmail(mixed $email) {
			$user = $this->_conn->prepare("SELECT * FROM `user` WHERE `email`='$email'");
			$user->execute();
			return $user;
		}



		// Methods required by ArrayAccess interface

		public function offsetGet(mixed $offset): mixed {
			return $this->_getByEmail($offset)->fetch();
		}



		public function offsetExists(mixed $offset): bool {
			return $this->_getByEmail($offset)->rowCount();
		}



		public function offsetSet(mixed $offset, mixed $value): void {
			if (!is_array($value)) throw new \Exception('$value must be an array');
			if (!is_string($offset)) throw new \Exception('$offset must be a string');
			if ($this->offsetExists($offset)) throw new \Exception('user already exists');

			$checkPassed = array_intersect($this->_requiredParams, array_keys($value));

			if (count($checkPassed) == count($this->_requiredParams)) {

				// Insert to database

				$inserting = $this->_conn->prepare("INSERT INTO `user` (`username`, `first_name`, `email`, `password`) VALUES (?, ?, ?, ?)");

				$inserting->execute([ $value['username'], $value['first_name'], $value['email'], sha1($value['password']) ]);

			}
		}



		public function offsetUnset(mixed $offset): void {
			if (!is_string($offset)) throw new \Exception('$offset must be a string');
			if (!$this->offsetExists($offset)) throw new \Exception('user not found!');

			// Delete from database

			$delete = $this->_conn->prepare("DELETE FROM `user` WHERE `email`='$offset'");
			$delete->execute();
		}



	}





	// CRUD Operations

	$user = new UserCollection();



	// ----------
	// Get a user
	// ----------

	var_dump($user["rokspeenork@gmail.com"]);



	// -----------
	// Check exist
	// -----------

	var_dump(isset($user["rokspeenork@gmail.com"])); 



	// ---------------
	// Create New User
	// ---------------

	$user["rokspeenork@gmail.com"] = [
		'username' => 'DevAbdelkader',
		'first_name' => "Abdelkader",
		'password' => 'helloworld',
		'email' => 'rokspeenork@gmail.com'
	]; 



	// -------------
	// Delete a user
	// -------------

	unset($user["abdelkader@gmail.com"]);

?>
