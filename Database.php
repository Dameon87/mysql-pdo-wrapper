<?php

class Database {

	private $conn;
	private $lastError;
	private $fields;
	private $values;
	private $binds;
	private $statement;

	public function __construct($dbname, $dbuser, $dbpass, $dbhost = 'localhost', $charset = 'utf8') {
		$dsn = 'mysql:dbname=' . $dbname . ';host=' . $dbhost . ';charset=' . $charset;
		try {
			$this->conn = new PDO($dsn, $dbuser, $dbpass);
		} catch (PDOException $e) {
			echo 'Database Connection Failed with Message: ' . $e->getMessage();
		}
	}

	public function insert($table, $data) {
		$this->parseData($data);

		$sql = "INSERT INTO " . $table . "(" . implode($this->fields, ', ') . ") VALUES(" . implode($this->binds, ', ') .")";
		$this->statement = $this->conn->prepare($sql);
		$this->Bind($data);
		$this->statement->execute();

		if ($this->statement->errorCode() === '00000') {
			return true;
		} else {
			$this->lastError = 'Failed: ' . $this->statement->errorInfo()[2];
			return false;
		}
	}

	public function update($table, $data, $where) {
		$this->parseData($data);

		$sql = "UPDATE " . $table . " SET " . implode($this->binds, ',') . " WHERE " . $where;
		$this->statement = $this->conn->prepare($sql);
		$this->Bind($data);
		$this->statement->execute();

		if ($this->statement->errorCode() === '00000' && $this->statement->rowCount() >= 1) {
            return true;
		} else if ($this->statement->rowCount() < 1) {
			$this->lastError = "Query Succeeded, but " . $this->statement->rowCount() . " Rows were affected.";
			return false;
        } else {
			$this->lastError = "Error: " . $this->errorInfo()[2];
			return false;
        }
	}

	public function select($table, $data, $fields = "*") {
		$this->parseData($data);

		$sql = "SELECT " . $fields . " FROM " . $table . " WHERE " . implode($this->binds, ',');
		$this->statement = $this->conn->prepare($sql);
		$this->Bind($data);
		$this->statement->execute();

		if ($this->statement->errorCode() === '00000' && $this->statement->rowCount() >= 1) {
			return $this->statement->fetchAll(PDO::FETCH_OBJ);
		} else if ($this->statement->rowCount() < 1) {
			$this->lastError = "Query Succeeded, but " . $this->statement->rowCount() . " Rows were affected.";
			return false;
		} else {
			$this->lastError = "Error: " . $this->errorInfo()[2];
			return false;
		}
	}

	public function parseData($data) {
		foreach ($data as $field => $value) {
            $this->fields[] = $field;
            $this->values[] = $value;
            $this->binds[] = "$field=:$field";
        }
	}

	public function Bind($data) {
		foreach ($data as $field => &$value) {
            $this->statement->bindParam(":$field", $value);
        }
	}

	public function getLastError() {
		return $this->lastError;
	}

	public function rowsAffected() {
		return $this->statement->rowCount();
	}

	public function getLastId() {
		return $this->statement->lastInsertId();
	}

	public function Reset() {
		unset($this->statement);
		unset($this->binds);
		unset($this->values);
		unset($this->fields);
	}
}
