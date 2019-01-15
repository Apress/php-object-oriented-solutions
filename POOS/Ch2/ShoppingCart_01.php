<?php
class Ch2_ShoppingCart
{
	public function addItem(Ch2_Book $item)
	{
		echo '<p>' . $item->getTitle() . ' added</p>';
	}
}
