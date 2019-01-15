<?php
$file = fopen('subfolder/newfile.txt', 'a');
$written = fwrite($file, "This was written using procedural code\n");
fclose($file);
echo $written . ' bytes written to file';
?>