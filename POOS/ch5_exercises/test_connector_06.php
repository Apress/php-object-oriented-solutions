<?php
require_once '../Pos/RemoteConnector.php';
$url = 'http://foundationphp.com/notthere.php';
try {
    $output = new Pos_RemoteConnector($url);
    echo $output;
} catch (Exception $e) {
    echo $e->getMessage();
}
?>