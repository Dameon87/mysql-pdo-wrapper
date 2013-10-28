mysql-pdo-wrapper
=================

MySQL PDO Wrapper

A very simple PDO Wrapper for making the process of prepared statements easier.

Example of use can be found in Examples.php

###Insert example:
```
$db->insert('Table', array('field' => 'value, 'field2' => 'value2');
```
#####Parameters: (Table, Array of Fields=>Values)

###Update example:
```
$db->insert('Table', array('field' => 'value, 'field2' => 'value2', 'field3="value3"');
```
#####Parameters: (Table, Array of Fields=>Values, WHERE)
