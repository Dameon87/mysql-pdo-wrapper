<?php
/**
* A simple wrapper for handling MySQL via PDO.
* @author Jonathon Bischof
* @copyright 2013 Jonathon Bischof
* @license http://www.gnu.org/licenses/gpl-3.0.html
* @version Alpha
*/

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
		$this->bind($data);
		$this->statement->execute();
		$this->reset();

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
		$this->bind($data);
		$this->statement->execute();
		$this->reset();

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
		$this->bind($data);
		$this->statement->execute();
		$this->reset();
		
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

	public function bind($data) {
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

	public function reset() {
		unset($this->binds);
		unset($this->values);
		unset($this->fields);
	}
}
