<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>What happens after moving the file pointer?</title>
<style type="text/css">
body {
	background-color:#fff;
	color:#000;
	font-family:Arial, Helvetica, sans-serif;
}
</style>
</head>

<body>
<?php
$file = new SplFileObject('sonnet116.txt');
// seek() counts from 0, so $file->seek(12) moves to the beginning of line 13
$file->seek(12);
echo '<p>Pointer moved to line 13.</p>';
echo '<ul><li><strong>$file->fgets():</strong> ' . $file->fgets() . '</li></ul>';
// Move the pointer back to line 13
$file->seek(12);
echo '<p>Pointer moved to line 13.</p>';
echo '<ul><li><strong>$file->getCurrentLine():</strong> ' . $file->getCurrentLine() . '</li></ul>';
// Move the pointer back to line 13
$file->seek(12);
echo '<p>Pointer moved to line 13.</p>';
echo '<ul><li><strong>$file->current():</strong> ' . $file->current() . '</li></ul>';
?>
</body>
</html>
