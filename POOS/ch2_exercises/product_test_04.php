<?php
require '../Ch2/Product_02.php';

$product = new Ch2_Product();
$product->setProductType('DVD');
echo $product->getProductType();
?>