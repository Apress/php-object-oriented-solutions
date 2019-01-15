<?php
$dir = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('.'));
$xmlFiles = new RegexIterator($dir, '/\.xml$/i');
foreach ($xmlFiles as $file) {
	  echo $file->getFilename() . '<br />';
}
?>