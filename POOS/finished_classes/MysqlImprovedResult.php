<?php
/**
 * A wrapper class for mysqli::query() to implement the Iterator and Countable interfaces.
 * 
 * Submits a MySQLi query and makes the mysqli_result object iterable and countable.
 * 
 * @package Pos
 * @author David Powers
 * @copyright David Powers 2008
 * @version 1.0.1
 */
class Pos_MysqlImprovedResult implements Iterator, Countable
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
     * @var mysqli_result
     */
    protected $_result;

    /**
     * Uses a MySQLi connection to submit a query and stores the result in the $_result property.
     *
     * @param string $sql         SQL query.
     * @param mysqli $connection  MySQLi connection to database.
     */
    public function __construct($sql, $connection)
    {
        if (!$this->_result = $connection->query($sql)) {
            throw new RuntimeException($connection->error . '. The actual query submitted was: ' . $sql);
        }
    }

    /**
     * Gets next row from database result and increments key.
     *
     */
    public function next()
    {
        $this->_current = $this->_result->fetch_assoc();
		$this->_valid = is_null($this->_current) ? false : true;
		$this->_key++;
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
            $this->_result->data_seek(0);
        }
        $this->_key = 0;
        $this->_current = $this->_result->fetch_assoc();
		$this->_valid = is_null($this->_current) ? false : true;
    }

    /**
     * Returns number of rows in database result.
     *
     * @return int
     */
    public function count()
    {
        return $this->_result->num_rows;
    }
}
