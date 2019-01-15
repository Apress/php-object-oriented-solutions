<?php
$file = new SplFileObject('subfolder/newfile.txt', 'a');
$written = $file->fwrite("This was written by an SplFileObject\n");
echo $written . ' bytes written to ' . $file->getFilename();
?>