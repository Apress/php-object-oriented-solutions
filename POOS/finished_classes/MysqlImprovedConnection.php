<?php
/**
 * A wrapper class for connecting to MySQL with MySQL Improved
 * 
 * Apart from the constructor and destructor, this has only one method:
 * getResultSet(), which returns a Pos_MysqlImprovedResult object.
 * 
 * @package Pos
 * @author David Powers
 * @copyright David Powers 2008
 * @version 1.0.0
 */
class Pos_MysqlImprovedConnection
{
    /**
     * MySQLi connection
     *
     * @var mysqli  Database connection using MySQL Improved.
     */
    protected $_connection;

    /**
     * Creates a database connection using MySQL Improved.
     *
     * @param string $host  Database server name.
     * @param string $user  Database user account.
     * @param string $pwd   User account password.
     * @param string $db    Name of database to connect to.
     */
    public function __construct($host, $user, $pwd, $db)
    {
        $this->_connection = @new mysqli($host, $user, $pwd, $db);
        if (mysqli_connect_errno()) {
            throw new RuntimeException('Cannot access database: ' . mysqli_connect_error());
        }
    }

    /**
     * Submits query to database and returns result as Pos_MysqlImprovedResult object.
     *
     * @param string $sql               SQL query.
     * @return Pos_MysqlImprovedResult  Result of query.
     */
    public function getResultSet($sql)
    {
        $results = new Pos_MysqlImprovedResult($sql, $this->_connection);
        return $results;
    }

    /**
     * Closes the database connection when object is destroyed.
     *
     */
    public function __destruct()
    {
        $this->_connection->close();
    }
}
