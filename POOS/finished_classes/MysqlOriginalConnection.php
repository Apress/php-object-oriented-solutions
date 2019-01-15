<?php
/**
 * A wrapper class for connecting to MySQL with the MySQL original extension.
 * 
 * Apart from the constructor and destructor, this has only one method:
 * getResultSet(), which returns a Pos_MysqlOriginalResult object.
 * 
 * @package Pos
 * @author David Powers
 * @copyright David Powers 2008
 * @version 1.0.0
 */
  class Pos_MysqlOriginalConnection {
    /**
     * MySQL connection
     *
     * @var resource  Database connection using the MySQL original extension.
     */
    protected $_connection;
    
    /**
     * Creates a database connection using the MySQL original extension.
     *
     * @param string $host  Database server name.
     * @param string $user  Database user account.
     * @param string $pwd   User account password.
     * @param string $db    Name of database to connect to.
     */
    public function __construct($host, $user, $pwd, $db) {
      $this->_connection = mysql_connect($host, $user, $pwd);
      if (mysql_errno()) {
        throw new RuntimeException('Cannot access database: '.mysql_error());
      }
      else {
        mysql_selectdb($db, $this->_connection);
      }
    }
    
    /**
     * Submits query to database and returns result as Pos_MysqlOriginalResult object.
     *
     * @param string $sql               SQL query.
     * @return Pos_MysqlOriginalResult  Result of query.
     */
    public function getResultSet($sql) {
      $results = new Pos_MysqlOriginalResult($sql, $this->_connection);
      return $results;
    }
    
    /**
     * Closes the database connection when object is destroyed.
     *
     */
    public function __destruct() {
      mysql_close($this->_connection);
    }
  }

