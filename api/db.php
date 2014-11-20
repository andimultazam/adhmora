<?php

class db {
    private $row_count;
    private $last_insert_id;
    
    public function __construct() {
	//-----------------------------------------------CONFIG
		$config['db_host'] = 'localhost';
        $config['db_user'] = 'babeh';
        $config['db_pass'] = 'aing';
        $config['db_name'] = 'adhmora';
	//-----------------------------------------------
        $con = mysql_connect($config['db_host'],$config['db_user'],$config['db_pass'],true) or die('Database connect error: '.mysql_errno());
        mysql_select_db($config['db_name'],$con) or die('Database select error: '.mysql_errno());
        $this->row_count = 0;
        $this->last_insert_id = 0;
    }
    
    public function exec($query) {
        $this->row_count = 0;
        $this->last_insert_id = 0;
        $result = mysql_query($query);
        if (!$result) {
            die('Database exec error: '.mysql_errno());
        } else {
            $this->row_count = mysql_affected_rows();
            $this->last_insert_id = mysql_insert_id();
        }
    }
    
    public function lastInsertId() {
        return $this->last_insert_id;
    }
    
    public function query($query,$raw=false) {
        $this->row_count = 0;
        $this->last_insert_id = 0;
        $result = mysql_query($query);
        if (!$result) {
            die('Database query error: '.mysql_errno());
        } else {
            $this->row_count = mysql_num_rows($result);
            if ($raw) {
                return $result;
            } else {
                $retval = array();
                while ($row = mysql_fetch_assoc($result)) {
                    $retval[] = $row;
                }
                mysql_free_result($result);
                return $retval;
            }
        }
    }
    
    public function rowCount() {
        return $this->row_count;
    }
}
