<?php
require_once 'Manufacturer.php';
abstract class Ch2_Product
{
    // properties defined here
    protected $_type;
    protected $_title;
    protected $_manufacturer;

    public function __construct()
    {
        $this->_manufacturer = new Ch2_Manufacturer();
    }

    // methods defined here
    public function getProductType()
    {
        return $this->_type;
    }

    public function getTitle()
    {
        return $this->_title;
    }

    abstract public function display();

    public function __clone()
    {
        $this->_manufacturer = clone $this->_manufacturer;
    }

    public function __call($method, $arguments)
    {
        // check that the other object has the specified method
        if (method_exists($this->_manufacturer, $method)) {
            // invoke the method and return any result
            return call_user_func_array(array($this->_manufacturer , $method), $arguments);
        }
    }
}