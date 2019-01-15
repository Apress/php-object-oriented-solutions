<?php
require_once '../Pos/MysqlImprovedConnection.php';
require_once '../Pos/MysqlImprovedResult.php';
try {
    $conn = new Pos_MysqlImprovedConnection('localhost', 'psadmin', 'kyoto', 'phpsolutions');
    $result = $conn->getResultSet('SELECT * FROM blog');
    foreach (new LimitIterator($result, 0, 1) as $row) {
        foreach ($row as $field => $value) {
            echo "$field: $value<br />";
        }
        echo '<br />';
    }
    echo '<p><strong>This is outside both loops.
          Now, back to the database results.</strong></p>';
    foreach (new LimitIterator($result, 1, 3) as $row) {
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