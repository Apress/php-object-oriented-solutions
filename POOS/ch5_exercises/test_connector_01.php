<?php
require_once '../Pos/RemoteConnector.php';
$url = 'http://friendsofed.com/news.php';
try {
    $output = new Pos_RemoteConnector($url);
} catch (Exception $e) {
    echo $e->getMessage();
}
?>