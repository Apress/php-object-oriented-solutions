<?php
require_once 'XmlExporter.php';
require_once 'Utils.php';

/**
 * A class for generating an RSS 2.0 feed from a MySQL database query.
 * 
 * Generates the RSS feed from a SQL query, and outputs it either to a file
 * or ready to send in response to a request.
 * 
 * @package Pos
 * @author David Powers
 * @copyright David Powers 2008
 * @version 1.0.0
 */
class Pos_RssFeed extends Pos_XmlExporter
{
    /**#@+
     *
     * @var string
    /**
     * Stores the RSS feed's channel title. 
     */
    protected $_feedTitle;
    /**
     * Stores the RSS feed's channel link. 
     */
    protected $_feedLink;
    /**
     * Stores the RSS feed's channel description. 
     */
    protected $_feedDescription;
    /**#@-*/
    /**
     * Specifies whether to use current date and time for lastBuildDate.
     *
     * @var bool
     */
    protected $_useNow;
    /**
     * Maps database column names to RSS 2.0 item elements. 
     *
     * @var array
     */
    protected $_itemArray;
    /**
     * Number of sentences to be extracted from beginning of text for each item's description. 
     *
     * @var int
     */
    protected $_numSentences;
    /**
     * Maximum number of records to be used as item elements in the RSS feed.
     *
     * @var int
     */
    protected $_maxRecords;
    /**
     * Stores the database column name for pubDate.
     *
     * @var string
     */
    protected $_itemPubDate;
    /**
     * Stores URL and query string for link elements.
     *
     * @var string
     */
    protected $_itemLink;
    /**
     * Specifies whether to build URL dynamically or use URL stored in database.
     *
     * @var bool
     */
    protected $_useURL;

    /**
     * Sets the title for the RSS channel.
     *
     * @param string $title  Channel title.
     */
    public function setFeedTitle($title)
    {
        $this->_feedTitle = $title;
    }

    /**
     * Sets the URL for the RSS channel.
     *
     * @param string $link  Channel URL.
     */
    public function setFeedLink($link)
    {
        $this->_feedLink = $link;
    }

    /**
     * Sets the description element for the RSS channel.
     *
     * @param string $description  Brief description of the feed.
     */
    public function setFeedDescription($description)
    {
        $this->_feedDescription = $description;
    }

    /**
     * Sets whether to use the current date and time for the channel's lastBuildDate element.
     * 
     * The default is to use the current date and time for the lastBuildDate
     * element. If the argument passed to this method is false, the most
     * recent pubDate from among the feed's item elements is used instead.
     *
     * @param bool $useNow  Defaults to true and uses current date and time for lastBuildDate.
     */
    public function setLastBuildDate($useNow = true)
    {
        $this->_useNow = $useNow;
    }

    /**
     * Sets the database column to be used for the title of each feed item.
     *
     * @param string $columnName  Name of database column to use for item titles.
     */
    public function setItemTitle($columnName)
    {
        $this->_itemArray['title'] = $columnName;
    }

    /**
     * Sets the database column and number of sentences for each item's description.
     * 
     * The second argument specifies the number of sentences to be extracted from the
     * beginning of the text stored in the database. Its use is optional. If not set,
     * it defaults to 2. If set to 0, the whole text is used.
     *
     * @param string $columnName  Name of database column to use for item descriptions.
     * @param int $numSentences   Number of sentences to use; default 2; if set to 0, all text is used.
     */
    public function setItemDescription($columnName, $numSentences = 2)
    {
        $this->_itemArray['description'] = $columnName;
        $this->_numSentences = $numSentences;
    }

    /**
     * Sets the database column and type for each item's pubDate.
     * 
     * The second argument specifies whether the date is stored as a MySQL or Unix timestamp.
     * Its use is optional. If not set, the default is MySQL. Any other value converts the
     * date and time from a Unix timestamp.
     *
     * @param string $columnName  Name of database column to use for item pubDate elements.
     * @param string $type        Storage type; default is MySQL format; any other value assumes Unix timestamp.
     */
    public function setItemPubDate($columnName, $type = 'MySQL')
    {
        $this->_itemPubDate = $columnName;
        $rssFormat = '%a, %d %b %Y %H:%i:%S';
        if (stripos($type, 'MySQL') === false) {
            $this->_itemArray['pubDate'] = "FROM_UNIXTIME($columnName, '$rssFormat')";
        } else {
            $this->_itemArray['pubDate'] = "DATE_FORMAT($columnName, '$rssFormat')";
        }
    }

    /**
     * Sets the database column to use for each item's link element.
     * 
     * This method should be used when the actual URL is stored in the database. It
     * is mutually incompatible with setItemLinkURL(), and throws an exception if
     * both methods are used in the same script.
     *
     * @param string $columnName  Name of database column to use for each item's link element.
     */
    public function setItemLink($columnName)
    {
        if (isset($this->_useURL)) {
            throw new LogicException('The methods setItemLink() and setItemLinkURL() are mutually exclusive. Use one or the other.');
        }
        $this->_itemArray['link'] = $columnName;
        $this->_useURL = false;
    }

    /**
     * Sets the URL to be used for dynamic display of each item.
     * 
     * This method builds the URL for each item's link element by querying the
     * database to find the table's primary key. It then generates a query string 
     * and adds it to the base URL supplied as an argument to the method.
     * 
     * It is mutually incompatible with setItemLink(), and throws and exception if
     * both methods are used in the same script.
     * 
     * Must be called after the setTable() method.
     *
     * @param string $url  Base URL for dynamic display of item.
     */
    public function setItemLinkURL($url)
    {
        if (isset($this->_useURL)) {
            throw new LogicException('The methods setItemLink() and setItemLinkURL() are mutually exclusive. Use one or the other.');
        }
        if (!isset($this->_tableName)) {
            throw new LogicException('You must set the table name with setTable() before calling setItemLinkURL().');
        }
        parent::usePrimaryKey($this->_tableName);
        if (is_array($this->_primaryKey)) {
            $this->_primaryKey = $this->_primaryKey[0];
        } else {
            throw new RuntimeException("Cannot determine primary key for $this->_tableName.");
        }
        $this->_itemArray['link'] = $this->_primaryKey;
        $this->_itemLink = $url . "?$this->_primaryKey=";
        $this->_useURL = true;
    }

    /**
     * Dummy method to override the inherited method and prevent its use. 
     *
     */
    public function usePrimaryKey()
    {}

    /**
     * Sets the table from which feed data is to be drawn and maximum number of items.
     * 
     * Second argument sets the maximum number of items. Its use is optional, and defaults
     * to 15. If set to 0, all items in the database are used.
     *
     * @param string $tableName  Name of database table that contains feed data.
     * @param int $maxRecords    Maximum number of items; default 15; if set to 0, all items retrieved.
     */
    public function setTable($tableName, $maxRecords = 15)
    {
        $this->_tableName = $tableName;
        $this->_maxRecords = is_numeric($maxRecords) ? (int) abs($maxRecords) : 15;
    }

    /**
     * Generates the RSS 2.0 feed and saves it to a file or outputs as a string.
     * 
     * Must be called after all other options have been set.
     *
     * @return int|string  If RSS feed written to file, returns number of bytes; otherwise returns feed as string.
     */
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
            throw new LogicException('Cannot generate RSS feed. Check the following item(s): ' . implode(', ', $error) . '.');
        }
        if (is_null($this->_sql)) {
            $this->buildSQL();
        }
        $resultSet = $this->_dbLink->getResultSet($this->_sql);
        if ($this->_useNow) {
            $lastBuildDate = date(DATE_RSS);
        } else {
            foreach (new LimitIterator($resultSet, 0, 1) as $row) {
                $lastBuildDate = $row['pubDate'];
            }
        }
        $rss = new XMLWriter();
        if (isset($this->_xmlFile)) {
            $fileOpen = @$rss->openUri($this->_xmlFile);
            if (!$fileOpen) {
                throw new RuntimeException("Cannot create $this->_xmlFile. Check permissions and that target folder exists.");
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
        foreach ($resultSet as $row) {
            $rss->startElement('item');
            foreach ($row as $field => $value) {
                if ($field == 'pubDate') {
                    $value = $this->getTimezoneOffset($value);
                } elseif ($field == 'link' && $this->_useURL) {
                    $value = $this->_itemLink . $value;
                } elseif ($field == 'description') {
                    $extract = Pos_Utils::getFirst($value, $this->_numSentences);
                    $value = $extract[0];
                }
                $rss->writeElement($field, $value);
            }
            $rss->endElement();
        }
        $rss->endElement();
        $rss->endElement();
        $rss->endDocument();
        return $rss->flush();
    }

    /**
     * Builds the SQL query to retrieve the data for the item elements of the RSS feed.
     * 
     * This method loops through the $_itemArray property mapping each column name to the
     * appropriate item element as an alias.
     */
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
            $select[] = "$column AS $alias";
        }
        // Join the column/alias pairs as a comma-delimited string
        $select = implode(', ', $select);
        // Build the SQL
        $this->_sql = "SELECT $select FROM $this->_tableName
                       ORDER BY $this->_itemPubDate DESC";
        // Add a LIMIT clause if $_maxRecords is not 0
        if ($this->_maxRecords) {
            $this->_sql .= " LIMIT $this->_maxRecords";
        }
        // Display the SQL for testing purposes
    // echo $this->_sql;
    }

    /**
     * Add the time zone offset to the pubDate element.
     *
     * @param string $pubDate  Date and time formatted as RFC 822 minus the time zone offset.
     * @return string          Date and time formatted as RFC 822 with time zone offset.
     */
    protected function getTimezoneOffset($pubDate)
    {
        if (class_exists('DateTime')) {
            $date = new DateTime($pubDate);
            return $date->format(DATE_RSS);
        } else {
            return $pubDate;
        }
    }
}
