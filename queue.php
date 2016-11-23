<?php

namespace diversen;

use RedbeanPHP\R;
use diversen\db\q;
use diversen\db\connect;

/**
 * Queue class where you can 
 * a) add values to a queue by 'name', 'unique', 'done'
 * b) select queues defined på queue names, e.g. 'email_reminder' or
 *    unique ids, e.g. 'email_reminder,12323 (userid)' and done = 0
 */
class queue {
    
    /**
     * Database table name of the queue
     * @var string $queue
     */
    public $queue = 'systemqueue';
    
    /**
     * Connect to database with a connection
     * @param resource $dsn
     */
    public function __construct ($dbh) {
        R::setup($dbh);
        R::freeze( TRUE );
	connect::$dbh = $dbh;
    }
    
    /**
     * Add a job to the queue with name, unique, object
     * @param string $name
     * @param string $unique
     * @return int $res
     */
    protected function create($name, $uniqueid) {
        $bean = R::dispense($this->queue);
        $bean->name = $name;
        $bean->uniqueid = $uniqueid;
        $bean->done = 0;
        
        return R::store($bean);
        
    }
    
    /**
     * Add a job only once to the queue with name, unique and object
     * @param string $name
     * @param string $unique
     * @param object $object
     * @return mixed $res int or false
     */
    public function addOnce($name, $uniqueid) {
        $row = $this->getQueueSingleRow($name, $uniqueid);
        if (empty($row)) {
            return $this->create($name, $uniqueid);
        }
        
        return false;
    }

    
    /**
     * Get all jobs in a queue, which needs to be executed. Only name
     * if required
     * @param string $name
     * @param mixed $uniqueid if null then only the above param 'name' will be used
     * @param mixed $done null | 1 | 0
     * @return array $rows
     */
    public function getQueueRows ($name, $uniqueid = null, $done = 0) {
        q::select($this->queue)->filter('name =', $name);
        
        if (!is_null($uniqueid)) {
            q::condition('AND');
            q::filter('uniqueid =', $uniqueid);
        }
        
        if (is_int($done)) {
            q::condition('AND');
            q::filter('done =', $done);
        }
        $rows = q::fetch();
        return $rows;
    }
    
    /**
     * Get single job in a queue, which needs to be executed
     * @param string $name
     * @param mixed $uniqueid
     * @param mixed $done
     * @return type
     */
    public function getQueueSingleRow ($name, $uniqueid, $done = null) {
        $rows = $this->getQueueRows($name, $uniqueid, $done);
        if (empty($rows)) {
            return [];
        }
        
        return $rows[0];
    }
    
    /**
     * Set a single queue row as done
     * @param array $row
     * @return int $res
     */
    public function setQueueRowDone($row) {
        
        $bean = R::findOne($this->queue, " id = ? ", [ $row['id'] ]);
        $bean->done = 1;
        return R::store($bean);
    }
    
    /**
     * Sets multiple queue rows as done
     * @param type $rows
     */
    public function setQueueRowsDone($rows) {
        
        R::begin();
        foreach($rows as $row) {
            $res = $this->setQueueRowDone($row);
            if (!$res) {
                return R::rollback();
            }
        }
        return R::commit();
    }
}
