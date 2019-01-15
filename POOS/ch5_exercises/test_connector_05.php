<?php
require_once '../Pos/RemoteConnector.php';
$url = 'http://fiendsofed.dom/new.php';
try {
    $output = new Pos_RemoteConnector($url);
    echo $output;
} catch (Exception $e) {
    echo $e->getMessage();
}
?>