<?php
/**
 * A wrapper class for the MySQL original extension query() funtion to implement the Iterator and Countable interfaces.
 * 
 * Submits a MySQL query and makes the result object iterable and countable.
 * 
 * @package Pos
 * @author David Powers
 * @copyright David Powers 2008
 * @version 1.0.1
 */
class Pos_MysqlOriginalResult implements Iterator, Countable
{
    /**
     * Stores value of current element.
     *
     * @var mixed
     */
    protected $_current;
    /**
     * Stores current element key.
     *
     * @var int
     */
    protected $_key;
    /**
     * Determines whether a current element exists.
     *
     * @var bool
     */
    protected $_valid;
    /**
     * Stores the database result.
     *
     * @var resource
     */
    protected $_result;

    /**
     * Uses a MySQL connection to submit a query and stores the result in the $_result property.
     *
     * @param string $sql           SQL query.
     * @param resource $connection  MySQL connection to database.
     */
    public function __construct($sql, $connection)
    {
        if (!$this->_result = mysql_query($sql, $connection)) {
            throw new Exception(mysql_error($connection) . '. The actual query submitted was: ' . $sql);
        }
    }

    /**
     * Gets next row from database result and increments key.
     *
     */
    public function next()
    {
        $this->_key ++;
        $this->_current = mysql_fetch_assoc($this->_result);
		$this->_valid = $this->_current === false ? false : true;
    }

    /**
     * Returns value of current element.
     *
     * @return mixed
     */
    public function current()
    {
        return $this->_current;
    }

    /**
     * Returns key of current element.
     *
     * @return int
     */
    public function key()
    {
        return $this->_key;
    }

    /**
     * Determines whether there is current element.
     *
     * @return bool
     */
    public function valid()
    {
        return $this->_valid;
    }

    /**
     * Moves to first row of database result.
     *
     */
    public function rewind()
    {
        if (!is_null($this->_key)) {
            mysql_data_seek($this->_result, 0);
        }
        $this->_key = 0;
        $this->_current = mysql_fetch_assoc($this->_result);
		$this->_valid = $this->_current === false ? false : true;
    }

    /**
     * Returns number of rows in database result.
     *
     * @return int
     */
    public function count()
    {
        return mysql_num_rows($this->_result);
    }
}

