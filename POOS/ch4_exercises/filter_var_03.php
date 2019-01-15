<?php
$var = '10.5';
$filtered = filter_var($var, FILTER_VALIDATE_INT);
var_dump($filtered);
?>