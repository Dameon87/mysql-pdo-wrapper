<?php

require_once('Database.php');

$db = new Database('Databse-name', 'Database-user', 'Database-pass');

// Set our variables
$table = 'users';
$username = 'example';
$email = 'user@example.net';
$name = 'Example User';
$data = array(
	'username' => $username,
	'email' => $email,
	'name' => $name
);
$where = 'email="user@example.com"';

// Lets test a simple insert.
if ($db->insert($table, $data)) {
	echo 'Data Inserted. ID: ' . $db->getLastId();
} else {
	echo 'Error: ' . $db->getLastError();
}

// Here is a simple update query.

if ($db->update($table, $data, $where)) {
	echo '[Success] : Rows Affected ' . $db->rowsAffected();
} else {
	echo '[Fail] : ' . $db->getLastError();
}
