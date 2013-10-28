<?php

require_once('Database.php');

$db = new Database('Databse-name', 'Database-user', 'Database-pass');

// Set our variables
$table = 'users';
$username = 'example';
$email = 'user@example.net';
$name = 'Example User';
$data = [
	'username' => $username,
	'email' => $email,
	'name' => $name
];
$selectdata = [
	'email' => $email
];
$where = ['email' => 'user@example.com'];

// Lets test a simple insert.
if ($db->insert($table, $data)) {
	echo '[Success] : Data Inserted. ID: ' . $db->getLastId();
} else {
	echo '[Error] : ' . $db->getLastError();
}

// Here is a simple update query.

if ($db->update($table, $data, $where)) {
	echo '[Success] : Rows Affected ' . $db->rowsAffected();
} else {
	echo '[Fail] : ' . $db->getLastError();
}

// Here is a simple select query.

$result = $db->select($table, $selectdata);
if ($result) {
    echo '[Success] : Rows Affected ' . $db->rowsAffected();
    var_dump($result);
} else {
    echo '[Fail] : ' . $db->getLastError();
}

// Here is a simple delete query.

$result = $db->delete($table, $where);
if ($result) {
    echo '[Success] : Rows Affected ' . $db->rowsAffected();
    var_dump($result);
} else {
    echo '[Fail] : ' . $db->getLastError();
}
