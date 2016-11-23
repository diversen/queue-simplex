<?php

include_once "vendor/autoload.php";

use diversen\queue;

//$dbh = new PDO('mysql:dbname=testinfo;host=localhost;charset=utf8', 'user', '');
$dbh = new PDO( 'sqlite:/tmp/sqlite.db' );

// 2. param will make database fluid - tables will be auto-generated.
$q = new queue($dbh, false);

// Add dummy job - in order to create database table
// Namespace dummy and jobid = dummy
$q->createTable();

// Just add some unique ids
$q->addOnce('test', uniqid());
$q->addOnce('test', uniqid());
$q->addOnce('test', uniqid());

// Get all rows that is not done
$rows = $q->getQueueRows('test');

foreach($rows as $row) {
    // You will properbly do something more useful than echo the row id
    // in real life .)
    // You can also use PDO transactions around your job and the queue 
    // job
    echo $row['id'] . PHP_EOL;
    $q->setQueueRowDone($row);
}

// Should all be done!
$rows = $q->getQueueRows('test');

// Should yields an empty array
echo "Jobs should be done - now an empty array\n";
print_r($rows);

// A single job with known unique id
$q->addOnce('test', 'this is a uniq id - but easy to remember');

// You will only be able to use this job once
$rows = $q->getQueueRows('test', 'this is a uniq id - but easy to remember');
foreach($rows as $row) {
    echo $row['id'] . PHP_EOL;
    $q->setQueueRowDone($row);
}

// Should all be done!
$rows = $q->getQueueRows('test', 'this is a uniq id - but easy to remember');

// Should yields an empty array
echo "Jobs should be done - now an empty array\n";
print_r($rows);

// You can only add a job with the same uniqueid once - so this will not create
// Another job
$q->addOnce('test', 'this is a uniq id - but easy to remember');

