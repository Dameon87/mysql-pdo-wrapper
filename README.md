mysql-pdo-wrapper
=================

MySQL PDO Wrapper

A very simple PDO Wrapper for making the process of prepared statements easier.

Example of use can be found in Examples.php

Requires PHP 5.4+ at this time.

All examples below include the Table argument. This can be ommitted with $db->setTable('TableName');
###Insert example:
```
$db->insert(array('field' => 'value, 'field2' => 'value2'), 'Table');
```
#####Parameters: (Array of Fields=>Values, Table)

###Update example:
```
$db->insert(array('field' => 'value', 'field2' => 'value2', 'field3="value3"'), 'Table');
```
#####Parameters: (Array of Fields=>Values, WHERE, Table)

###Select example:
```
$db->select(array('field' => 'value'), 'fields,defaults,to,*', 'Table');
```
#####Parameters: (Array of Fields=>Values, Fields, Table)

###Delete example:
```
$db->delete(array('field' => 'value'), array('field' => 'value'), 'Table');
```
#####Parameters: (Array of Fields=>Values, Array of Search Fields=>Values, Table)