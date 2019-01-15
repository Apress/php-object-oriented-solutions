<?php
class Ch2_ShoppingCart
{
	public function addItem(Ch2_Product $item)
	{
		echo '<p>' . $item->getTitle() . ' added</p>';
	}
}
