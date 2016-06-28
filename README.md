# queue-simplex

A simple queue system for PHP based on one of the following databases: 

It should support SQLite, MariaDB, PostgreSQL (and maybe Cubrid)

# Install

    composer require diversen/queue-simplex

Example of a table can be found in <mysql.sql>

Add a row to queue:

~~~php
// $dbh is a PDO database handle

$unique = "usage_notify"; // You can also add e.g. a user_id or something more unique

// Add a job once, and only once
$res = $q->addOnce('usage', $unique);
~~~

At a later time, e.g. in a cron job get the queue rows: 

~~~php
$q = new queue($dbh);
$rows = $q->getQueueRows('usage', $unique, $done = 0);
if (!empty($rows)) {
    // Do something with the info from the rows
    // This will set all rows as done
    // $q->setQueueRowsDone($rows);
    // Or iterate over the rows while doing something
    // This way you can use PDO begin, commit and rollback 
    foreach($rows as $row) {
         // do a job based on the queue row
         setQueueRowDone($row);
    }
} 

~~~
