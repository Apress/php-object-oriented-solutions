<?php
$var = '100,000.98';
$filtered = filter_var($var, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
var_dump($filtered);
?>