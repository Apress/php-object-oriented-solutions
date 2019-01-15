<?php
$var = 10;
$filtered = filter_var($var, filter_id('float'));
var_dump($filtered);
?>