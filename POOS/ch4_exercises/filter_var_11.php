<?php
$var = '10,5';
$filtered = filter_var($var, FILTER_VALIDATE_FLOAT, array('options' => array('decimal' => ',')));
var_dump($filtered);
?>