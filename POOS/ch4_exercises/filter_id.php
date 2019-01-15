<?php
$filters = filter_list();
foreach ($filters as $filter) {
    echo $filter . ': ' . filter_id($filter) . '<br />';
}
?>