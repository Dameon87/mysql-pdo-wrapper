<?php
/**
* A simple wrapper for handling MySQL via PDO.
* @author Jonathon Bischof
* @copyright 2013 Jonathon Bischof
* @license http://www.gnu.org/licenses/gpl-3.0.html
* @version Alpha
*/
namespace ASOJon\Database;

class Database {

    private $conn;
	private $lastError;
	private $fields;
	private $values;
	private $binds;
	private $where;
	private $statement;
	public $table;

	public function __construct($dbname, $dbuser, $dbpass, $dbhost = 'localhost', $charset = 'utf8') {
		$dsn = 'mysql:dbname=' . $dbname . ';host=' . $dbhost . ';charset=' . $charset;
		try {
			$this->conn = new \PDO($dsn, $dbuser, $dbpass);
		} catch (\PDOException $e) {
			echo 'Database Connection Failed with Message: ' . $e->getMessage();
		}
	}

	public function insert($data, $table = null) {
		if (!$table) { $table = $this->table; }
		$this->parseData($data);

		foreach ($data as $field => $key) {
			$inserts[] = ":$field";
		}

		$sql = "INSERT INTO " . $table . " (" . implode($this->fields, ', ') . ") VALUES(" . implode($inserts, ',') .")";
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

	public function update($data, $where, $table = null) {
        	if (!$table) { $table = $this->table; }
		$this->parseData($data, $where);

		$sql = "UPDATE " . $table . " SET " . implode($this->binds, ',') . " WHERE " . implode($this->where, ' AND ');
		$this->statement = $this->conn->prepare($sql);
		$this->bind($data);
		$this->bind($where);
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

	public function select($data, $fields = "*", $table = null) {
        if (!$table) { $table = $this->table; }
		$this->parseData($data);

		$sql = "SELECT " . $fields . " FROM " . $table . " WHERE " . implode($this->binds, ' AND ');
		$this->statement = $this->conn->prepare($sql);
		$this->bind($data);
		$this->statement->execute();
		$this->reset();
		if ($this->statement->errorCode() === '00000' && $this->statement->rowCount() >= 1) {
			return $this->statement->fetchAll(\PDO::FETCH_OBJ);
		} else if ($this->statement->rowCount() < 1) {
			$this->lastError = "Query Succeeded, but " . $this->statement->rowCount() . " Rows were affected.";
			return false;
		} else {
			$this->lastError = "Error: " . $this->errorInfo()[2];
			return false;
		}
	}

	public function delete($data, $table = null) {
        if (!$table) { $table = $this->table; }
		$this->parseData($data);
		$sql = "DELETE FROM " . $table . " WHERE " . implode($this->binds, ' AND ');
		$this->statement = $this->conn->prepare($sql);
		$this->bind($data);
		$this->statement->execute();
		$this->reset();
		if ($this->statement->errorCode() === '00000' && $this->statement->rowCount() >=1) {
			return $this->statement->fetchAll(PDO::FETCH_OBJ);
		} else if ($this->statement->rowCount() < 1) {
			$this->lastError = "Query Succeeded, but " . $this->statement->rowCount() . " Rows were affected.";
			return false;
		} else {
			$this->lastError = "Error: " . $this->errorInfo()[2];
			return false;
		}
	}

	public function parseData($data, $where = null) {
		foreach ($data as $field => $value) {
            $this->binds[] = "$field=:$field";
			$this->fields[] = $field;
			//$this->inserts[] = ":$field";
        }
		if ($where) {
        	foreach ($where as $wfield => $wvalue) {
        		$this->where[] = "$wfield=:$wfield";
        	}
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

	public function setTable($table) {
		$this->table = $table;
	}

	public function reset() {
		unset($this->binds);
		unset($this->fields);
		unset($this->where);
	}
}
