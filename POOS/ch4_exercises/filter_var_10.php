<?php
$var = 17;
$filtered = filter_var($var, FILTER_VALIDATE_INT, array('options' => array('min_range' => 5,
                                                                           'max_range' => 10)));
var_dump($filtered);
?>