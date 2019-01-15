<?php
require_once '../Pos/MysqlImprovedConnection.php';
require_once '../Pos/MysqlImprovedResult.php';
try {
    $conn = new Pos_MysqlImprovedConnection('localhost', 'psadmin', 'kyoto', 'phpsolutions');
    $result = $conn->getResultSet('SELECT * FROM blog');
    foreach ($result as $row) {
        foreach ($row as $field => $value) {
            echo "$field: $value<br />";
        }
        echo '<br />';
    }
} catch (RuntimeException $e) {
    echo 'This is a RuntimeException: ' . $e->getMessage();
} catch (Exception $e) {
    echo 'This is an ordinary Exception: ' . $e->getMessage();
}
?>