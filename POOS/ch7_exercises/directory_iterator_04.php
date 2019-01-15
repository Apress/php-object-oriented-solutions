<?php
$dir = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('.'));
foreach ($dir as $file) {
	  echo $file->getFilename() . '<br />';
}
?>