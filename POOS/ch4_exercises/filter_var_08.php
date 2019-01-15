<?php
$var = 100.98;
$filtered = filter_var($var, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
var_dump($filtered);
?>