<?php
require_once '../Pos/RemoteConnector.php';
try {
  $foed = new Pos_RemoteConnector('http://friendsofed.com/news.php');
  if ($foed) {
	$xml = simplexml_load_string($foed);
	if ($xml->asXML('foed.xml')) {
		echo 'XML saved';
	} else {
		echo 'Could not save XML';
	}
  } else {
	  echo $foed->getErrorMessage();
  }
} catch (Exception $e) {
	echo $e->getMessage();
}
?>
