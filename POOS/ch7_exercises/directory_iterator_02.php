<?php
$dir = new DirectoryIterator('.');
foreach ($dir as $file) {
	if (!$file->isDot() && !$file->isDir()) {
	  echo $file . '<br />';
	}
}
?>