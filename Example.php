<?php

require_once('Database.php');

$db = new Database('Databse-name', 'Database-user', 'Database-pass');
// Optionally set the table here. This is not required. You can simply call any of the insert/update/delete/select methods with the first parameter as the Table. Otherwise, simply omit it!
// Examples will omit the table parameter as we set it below!
$db->setTable('Users');
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
$selectdata = ['email' => $email];
$where = ['email' => 'user@example.com'];

// Lets test a simple insert.
if ($db->insert($data)) {
	echo '[Success] : Data Inserted. ID: ' . $db->getLastId();
} else {
	echo '[Error] : ' . $db->getLastError();
}

// Here is a simple update query.

if ($db->update($data, $where)) {
	echo '[Success] : Rows Affected ' . $db->rowsAffected();
} else {
	echo '[Fail] : ' . $db->getLastError();
}

// Here is a simple select query.

$result = $db->select($selectdata);
if ($result) {
    echo '[Success] : Rows Affected ' . $db->rowsAffected();
    var_dump($result);
} else {
    echo '[Fail] : ' . $db->getLastError();
}

// Here is a simple delete query.

$result = $db->delete($where);
if ($result) {
    echo '[Success] : Rows Affected ' . $db->rowsAffected();
    var_dump($result);
} else {
    echo '[Fail] : ' . $db->getLastError();
}
