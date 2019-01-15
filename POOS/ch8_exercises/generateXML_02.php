<?php
$xml = new XMLWriter();
$xml->openUri('test.xml');
$xml->setIndent(true);
$xml->setIndentString("\t");
$xml->startDocument();
$xml->startElement('inventory');
$xml->startElement('book');
$xml->writeAttribute('isbn13', '978-1-43021-011-5');
$xml->writeElement('title', 'PHP Object-Oriented Solutions');
$xml->writeElement('author', 'David Powers');
$xml->endElement();
$xml->startElement('book');
$xml->writeAttribute('isbn13', '978-1-59059-819-1');
$xml->writeElement('title', 'Pro PHP: Patterns, Frameworks, Testing and More');
$xml->writeElement('author', 'Kevin McArthur');
$xml->endElement();
$xml->endElement();
$xml->endDocument();
if ($xml->flush()) {
    echo 'XML created';
} else {
    echo 'Problem with XML';
}
?>