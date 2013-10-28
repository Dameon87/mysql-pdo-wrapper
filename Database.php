<?php

class Database {

	private $table;
	private $conn;
	private $sql;
	private $lastError;
	private $rows;

	public function __construct($dbname, $dbuser, $dbpass, $dbhost = 'localhost', $charset = 'utf8') {
		$dsn = 'mysql:dbname=' . $dbname . ';host=' . $dbhost . ';charset=' . $charset;
		try {
			$this->conn = new PDO($dsn, $dbuser, $dbpass);
		} catch (PDOException $e) {
			echo 'Database Connection Failed with Message: ' . $e->getMessage();
		}
	}
	
	public function insert($table, $data) {
		$fields = '';
		$values = '';
		$binds = '';
		foreach ($data as $field => $value) {
			$fields[] = $field;
			$binds[] = ":$field";
			$values[] = $value;
		}
		$sql = "INSERT INTO " . $table . "(" . implode($fields, ', ') . ") VALUES(" . implode($binds, ', ') .")";
		$statement = $this->conn->prepare($sql); //Actually make a PDO prepare statement here.
		
		foreach ($data as $field => &$value) {
			$statement->bindParam(":$field", $value);
		}
		
		$statement->execute();
		
		if ($statement->errorCode() === '00000') {
			$this->rows = $statement->rowCount();
			return true;
		} else {
			$this->lastError = 'Failed: ' . $statement->errorInfo()[2];
			return false;
		}
	}

	public function update($table, $data, $where) {
		foreach ($data as $field => $value) {
			$fields[] = $field;
			$values[] = $value;
			$binds[] = "$field=:$field";
		}
		$sql = "UPDATE " . $table . " SET " . implode($binds, ',') . " WHERE " . $where;
		$statement = $this->conn->prepare($sql);
		
		foreach ($data as $field => &$value) {
			$statement->bindParam(":$field", $value);
		}
		
		$statement->execute();

		if ($statement->errorCode() === '00000' && $statement->rowCount() >= 1) {
			$this->rows = $statement->rowCount();
            		return true;
		} else if ($statement->rowCount() < 1) {
			$this->lastError = "Query Succeeded, but " . $statement->rowCount() . " Rows were affected.";
			return false;
        	} else {
			$this->lastError = "Error: " . $this->errorInfo()[2];
			return false;
        	}
	}

	public function getLastError() {
		return $this->lastError;
	}

	public function rowsAffected() {
		return $this->rows;
	}
}
