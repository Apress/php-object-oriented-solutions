<?php
$data = array('age'         => 21,
              'rating'      => 4,
              'price'       => 9.95,
              'thousands'   => '100,000.95',
              'european'    => '100.000,95');

$instructions = array('age'         => FILTER_VALIDATE_INT,
                      'rating'      => array('filter'  => FILTER_VALIDATE_INT,
                                             'options' => array('min_range' => 1,
                                                                'max_range' => 5)),
                      'price'       => array('filter'  => FILTER_SANITIZE_NUMBER_FLOAT,
                                             'flags'   => FILTER_FLAG_ALLOW_FRACTION),
                      'thousands'   => array('filter'  => FILTER_SANITIZE_NUMBER_FLOAT,
                                             'flags'   => FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND),
                      'european'    => array('filter'  => FILTER_VALIDATE_FLOAT,
                                             'options' => array('decimal' => ','),
                                             'flags'   => FILTER_FLAG_ALLOW_THOUSAND));
$filtered = filter_var_array($data, $instructions);
var_dump($filtered);
?>