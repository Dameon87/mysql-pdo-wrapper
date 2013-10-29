mysql-pdo-wrapper
=================

MySQL PDO Wrapper

A very simple PDO Wrapper for making the process of prepared statements easier.

Example of use can be found in Examples.php

Requires PHP 5.4+ at this time.

All examples below include the Table argument. This can be ommitted with $db->setTable('TableName');
###Insert example:
```
$db->insert('Table', array('field' => 'value, 'field2' => 'value2');
```
#####Parameters: (Table, Array of Fields=>Values)

###Update example:
```
$db->insert('Table', array('field' => 'value', 'field2' => 'value2', 'field3="value3"');
```
#####Parameters: (Table, Array of Fields=>Values, WHERE)

###Select example:
```
$db->select('Table', array('field' => 'value'), 'fields,defaults,to,*');
```
#####Parameters: (Table, Array of Fields=>Values, Fields)

###Delete example:
```
$db->delete('Table', array('field' => 'value'), array('field' => 'value'));
```
#####Parameters: (Table, Array of Fields=>Values, Array of Search Fields=>Values)