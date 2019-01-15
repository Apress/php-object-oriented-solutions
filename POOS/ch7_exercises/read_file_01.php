<?php
$dir = new DirectoryIterator('subfolder');
foreach ($dir as $file) {
	// Make sure it's not a dot file or directory
	if (!$file->isDot() && !$file->isDir()) {
		// Open the file
	    $currentFile = $file->openFile();
	    // Loop through each line of the file and display it
	    foreach ($currentFile as $line) {
		  echo $line . '<br />';
	    }
	  echo '<br />';
	}
}	
?>
