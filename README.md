# queue-simplex

A simple queue system for PHP based on one of the following databases: 

It should support MySQL, SQLite, MariaDB, PostgreSQL (and maybe Cubrid)

Tested with MySQL

# Install

    composer require diversen/queue-simplex

Example of a table can be found on <https://github.com/diversen/queue-simplex/blob/master/mysql.sql>

Add a row to queue:

~~~php
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

~~~
