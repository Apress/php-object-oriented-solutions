<?php
require_once '../Pos/RemoteConnector.php';
$url = 'http://friendsofed.com/new.php';
try {
    $output = new Pos_RemoteConnector($url);
    if (strlen($output)) {
        echo $output;
    } else {
        echo $output->getErrorMessage();
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
?>