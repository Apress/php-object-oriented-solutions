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
	
	public function setManufacturerName($name)
	{
	    $this->_manufacturer->setManufacturerName($name);
	}
	
	public function getManufacturerName()
	{
	    return $this->_manufacturer->getManufacturerName();
	}
	
	public function __clone()
	{
	    $this->_manufacturer = clone $this->_manufacturer;
	}
}