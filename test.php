<?php

include_once "vendor/autoload.php";

use diversen\queue;

$dbh = new PDO('mysql:dbname=testinfo;host=localhost;charset=utf8', 'user', '');
$q = new queue($dbh);

// Just add some unique ids
$q->addOnce('test', uniqid());
$q->addOnce('test', uniqid());
$q->addOnce('test', uniqid());

// Get all rows that is not done
$rows = $q->getQueueRows('test');

foreach($rows as $row) {
    // You will properbly do something more useful than echo the row id
    // in real life .)
    echo $row['id'] . PHP_EOL;
    $q->setQueueRowDone($row);
}

// Should all be done!
$rows = $q->getQueueRows('test');

// Should yields an empty array
print_r($rows);
