<?php

require_once 'Product_03.php';

class Ch2_DVD extends Ch2_Product
{
	protected $_duration;
	
	public function __construct($title, $duration)
	{
		$this->_title = $title;
		$this->_duration = $duration; 
		$this->_type = 'DVD';
	}
	
	public function getDuration()
	{
		return $this->_duration;
	}
}
