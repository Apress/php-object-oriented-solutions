<?php
$var = '100.789,5';
$filtered = filter_var($var, FILTER_VALIDATE_FLOAT, array('options' => array('decimal' => ','),
                                                          'flags'   => FILTER_FLAG_ALLOW_THOUSAND));
var_dump($filtered);
?>