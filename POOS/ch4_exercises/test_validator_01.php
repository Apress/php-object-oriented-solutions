<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Test validator class</title>
<style type="text/css">
<!--
body {
	font-family: Arial, Helvetica, sans-serif;
	color: #000;
	background-color: #FFF;
}

label {
	font-weight: bold;
	display: block;
}

form {
	margin-left: 50px;
}

#comments {
	height: 100px;
	width: 400px;
}

.textfield {
	width: 250px;
}

.warning {
	color: #f00;
	font-weight: bold;
}
-->
</style>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
<p><label for="name">Name:</label> <input name="name" type="text"
	class="textfield" id="name" /></p>
<p><label for="email">Email:</label> <input name="email" type="text"
	class="textfield" id="email" /></p>
<p><label for="comments">Comments:</label> <textarea name="comments"
	id="comments" cols="45" rows="5"></textarea></p>
<p><input type="submit" name="send" id="send" value="Send comments" /></p>
</form>
</body>
</html>
