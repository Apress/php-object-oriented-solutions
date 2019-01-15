<?php
$file = new SplFileObject('sonnet116.txt');
foreach ($file as $line) {
  echo $line . '<br />';
}
?>