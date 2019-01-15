<?php
$xml = simplexml_load_file('foed.xml');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Inspecting namespaces in SimpleXML</title>
<link href="simplexml.css" rel="stylesheet" type="text/css" />
</head>

<body>
<pre>
<h1>getNamespaces() &#8212; Returns namespaces used in the root element</h1>
<?php
$ns = $xml->getNamespaces();
print_r($ns);
?>
<h1>getNamespaces(true) &#8212; Returns namespaces used in all elements</h1>
<?php
$ns = $xml->getNamespaces(true);
print_r($ns);
?>
<h1>getDocNamespaces() &#8212; Returns namespaces declared in the root element</h1>
<?php
$ns = $xml->getDocNamespaces();
print_r($ns);
?>
<h1>getDocNamespaces(true) &#8212; Returns all declared namespaces</h1>
<?php
$ns = $xml->getDocNamespaces(true);
print_r($ns);
?>
</pre>
</body>
</html>
