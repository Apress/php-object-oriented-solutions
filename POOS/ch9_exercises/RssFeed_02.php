<?php
require_once 'XmlExporter.php';
class Pos_RssFeed extends Pos_XmlExporter
{
    protected $_feedTitle;
    protected $_feedLink;
    protected $_feedDescription;
    protected $_useNow;
    protected $_itemArray;
    protected $_numSentences;
    protected $_maxRecords;

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

    public function setItemTitle($columnName)
    {
        $this->_itemArray['title'] = $columnName;
    }

    public function setItemDescription($columnName, $numSentences = 2)
    {
        $this->_itemArray['description'] = $columnName;
        $this->_numSentences = $numSentences;
    }

    public function setTable($tableName, $maxRecords = 15)
    {
        $this->_tableName = $tableName;
        $this->_maxRecords = is_numeric($maxRecords) ? (int) abs($maxRecords) : 15;
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
        if (is_null($this->_sql)) {
            $this->buildSQL();
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

    protected function buildSQL()
    {
        if (!isset($this->_tableName)) {
            throw new LogicException('No table defined. Use the setTable() method.');
        }
        if (!isset($this->_itemArray['description']) && !isset($this->_itemArray['title'])) {
            throw new LogicException('RSS items must have at least a description or a title.');
        }
        // Initialize an empty array for the column names
        $select = array();
        // Loop through the $_itemArray property to build the list of aliases
        foreach ($this->_itemArray as $alias => $column) {
            $select[] = "$column as $alias";
        }
        // Join the column/alias pairs as a comma-delimited string
        $select = implode(', ', $select);
        // Build the SQL
        $this->_sql = "SELECT $select FROM $this->_tableName";
        // Add a LIMIT clause if $_maxRecords is not 0
        if ($this->_maxRecords) {
            $this->_sql .= " LIMIT $this->_maxRecords";
        }
        // Display the SQL for testing purposes
        echo $this->_sql;
    }
}
