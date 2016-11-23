# queue-simplex

A simple queue system for PHP based on one of the following databases: 

It should support MySQL, SQLite, MariaDB, PostgreSQL (and maybe Cubrid)

Tested with MySQL

# Install

    composer require diversen/queue-simplex

Example of a MySQL table can be found on <https://github.com/diversen/queue-simplex/blob/master/mysql.sql>

(It should just contain the fields `id`, `name`, `uniqueid` and `done` - as in the above MySQL table)

Code example: <https://github.com/diversen/queue-simplex/blob/master/test.php>

