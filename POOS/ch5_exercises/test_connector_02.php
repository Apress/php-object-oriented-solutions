<?php
require_once '../Pos/RemoteConnector.php';
$url = 'htp://friendsofed.com/news.php';
try {
    $output = new Pos_RemoteConnector($url);
} catch (Exception $e) {
    echo $e->getMessage();
}
?>