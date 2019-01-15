<?php
$xml = new XMLWriter();
$xml->openMemory();
$xml->startDocument();
$xml->startElement('inventory'); // open root element
$xml->startElement('book');      // open top-level node
$xml->writeAttribute('isbn13', '978-1-43021-011-5');
$xml->writeElement('title', 'PHP Object-Oriented Solutions');
$xml->writeElement('author', 'David Powers');
$xml->endElement();             // close first <book> node
$xml->startElement('book');     // open next <book> node
$xml->writeAttribute('isbn13', '978-1-59059-819-1');
$xml->writeElement('title', 'Pro PHP: Patterns, Frameworks, Testing and More');
$xml->writeElement('author', 'Kevin McArthur');
$xml->endElement();            // close second <book> node
$xml->endElement();            // close root element
$xml->endDocument();
header('Content-Type: text/xml');
echo $xml->flush();
?>