<?php

include_once "../../autoload.php";
include_once "../../diversen/redbean-composer/rb.php";
use diversen\queue;

$dbh = new PDO('mysql:dbname=testinfo;host=localhost;charset=utf8', 'user', 'password');
$q = new queue($dbh);
$q->addOnce('test', uniqid());

