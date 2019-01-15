<?php
require_once 'XmlExporter.php';
class Pos_RssFeed extends Pos_XmlExporter
{
    protected $_feedTitle;
    protected $_feedLink;
    protected $_feedDescription;
    protected $_useNow;

    public function setFeedTitle($title)
    {
        $this->_feedTitle = $title;
    }

    public function setFeedLink($link)
    {
        $this->_feedLink = $link;
    }

    public function setFeedDescription($description)
    {
        $this->_feedDescription = $description;
    }

    public function setLastBuildDate($useNow = true)
    {
        $this->_useNow = $useNow;
    }

    public function generateXML()
    {
        $error = array();
        if (!isset($this->_feedTitle)) {
            $error[] = 'feed title';
        }
        if (!isset($this->_feedLink)) {
            $error[] = 'feed link';
        }
        if (!isset($this->_feedDescription)) {
            $error[] = 'feed description';
        }
        if ($error) {
            throw new Exception('Cannot generate RSS feed. Check the following item(s): ' . implode(', ', $error) . '.');
        }
        if ($this->_useNow) {
            $lastBuildDate = date(DATE_RSS);
        }
        $rss = new XMLWriter();
        if (isset($this->_xmlFile)) {
            $fileOpen = @$rss->openUri($this->_xmlFile);
            if (!$fileOpen) {
                throw new Exception("Cannot create $this->_xmlFile. Check permissions and that target folder exists.");
            }
            $rss->setIndent($this->_indent);
            $rss->setIndentString($this->_indentString);
        } else {
            $rss->openMemory();
        }
        $rss->startDocument();
        $rss->startElement('rss');
        $rss->writeAttribute('version', '2.0');
        $rss->startElement('channel');
        $rss->writeElement('title', $this->_feedTitle);
        $rss->writeElement('link', $this->_feedLink);
        $rss->writeElement('description', $this->_feedDescription);
        $rss->writeElement('lastBuildDate', $lastBuildDate);
        $rss->writeElement('docs', 'http://www.rssboard.org/rss-specification');
        
        // Code to generate <item> elements goes here
        
        $rss->endElement();
        $rss->endElement();
        $rss->endDocument();
        return $rss->flush();
    }
}
