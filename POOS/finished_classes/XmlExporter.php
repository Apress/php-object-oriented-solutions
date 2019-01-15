<?php
/**
 * A class for generating XML from a MySQL database query.
 * 
 * Generates XML from a SQL query, and outputs it either to a file
 * or ready to send in response to a request.
 * 
 * @package Pos
 * @author David Powers
 * @copyright David Powers 2008
 * @version 1.0.0
 */
class Pos_XmlExporter
{
    /**
     * Stores a MySQL database connection.
     *
     * @var Pos_MysqlImprovedConnection
     */
    protected $_dbLink;
    /**#@+
     *
     * @var string
     */
    /**
     * SQL query that retrieves data to be output as XML.
     */
    protected $_sql;
    /**
     * Custom name for root element.
     */
    protected $_docRoot;
    /**
     * Custom name for top-level nodes.
     */
    protected $_element;
    /**
     * Name of table whose primary key is to be used a attribute in opening tag of top-level elements.
     */
    protected $_primaryKey;
    /**
     * Path and filename of file that generated XML is to be written to.
     */
    protected $_xmlFile;
    /**
     * String defining how child nodes are to be indented when written to file.
     */
    protected $_indentString;
    /**#@-*/
    /**
     * Flag that determines whether to indent child nodes when written to file.
     *
     * @var bool
     */
    protected $_indent;

    /**
     * Constructor - establishes connection to database.
     * 
     * The constructor checks that XMLWriter and MySQLi are installed, and uses the
     * database login details to create a Pos_MysqlImprovedConnection object.
     *
     * @param string $server   Name of the database server host.
     * @param string $username Name of user account for database connection.
     * @param string $password User account password.
     * @param string $database Name of database to be queried.
     */
    public function __construct($server, $username, $password, $database)
    {
        if (!class_exists('XMLWriter')) {
            throw new LogicException('Pos_XmlExporter requires the PHP core class XMLWriter.');
        }
        if (!class_exists('mysqli')) {
            throw new LogicException('MySQL Improved not installed. Check PHP configuration and MySQL version.');
        }
        $this->_dbLink = new Pos_MysqlImprovedConnection($server, $username, $password, $database);
    }

    /**
     * Sets the SQL query for the data required to be output as XML.
     * 
     * The order and number of items in the XML output is determined solely
     * by the SQL query. If the database uses column names that would be 
     * illegal as XML tag names, create a suitable alias in the SQL for
     * such columns. 
     *
     * @param string $sql SQL query to retrieve data to be output as XML.
     */
    public function setQuery($sql)
    {
        $this->_sql = $sql;
    }

    /**
     * Sets custom names for the root and top-level nodes of the XML document.
     * 
     * Using this method is optional. If custom names are not set using this method,
     * the class uses the default names ("root" and "row").
     * 
     * Calls the protected checkValidName() method to ensure that the names
     * comply with XML tag naming rules.
     *
     * @param string $docRoot  Custom name for root element.
     * @param string $element  Custom name for top-level nodes.
     */
    public function setTagNames($docRoot, $element)
    {
        $this->_docRoot = $this->checkValidName($docRoot);
        $this->_element = $this->checkValidName($element);
    }

    /**
     * Sets the name of the table from which the primary key is to be used as an attribute
     * in the opening tag of each top-level node.
     * 
     * Using this method is optional. If not used, the primary key, if any, is included
     * as a normal child node. Otherwise, the method queries the database, and stores in
     * the $_primaryKey property an array containing the name(s) of the table's primary
     * key(s). 
     *
     * @param string $table  Name of database table from which primary key is to be extracted.
     */
    public function usePrimaryKey($table)
    {
        $getIndex = $this->_dbLink->getResultSet("SHOW INDEX FROM $table");
        foreach ($getIndex as $row) {
            if ($row['Key_name'] == 'PRIMARY') {
                $this->_primaryKey[] = $row['Column_name'];
            }
        }
    }

    /**
     * Sets the path and filename of file to which XML is to be written.
     * 
     * Using this method is optional. If used, the class uses the XMLWriter::openUri()
     * method to write the output to file. If not used, XMLWriter::openMemory() is called
     * instead to generate XML to be captured in a variable.
     *
     * @param string $pathname      Name of file (and path, if required) to write XML to.
     * @param bool $indent          Determines whether child nodes are indented; defaults to true.
     * @param string $indentString  Defines the string to be used to indent child nodes.
     */
    public function setFilePath($pathname, $indent = true, $indentString = "\t")
    {
        $this->_xmlFile = $pathname;
        $this->_indent = $indent;
        $this->_indentString = $indentString;
    }

    /**
     * Implements the options set by other methods, and generates the XML output.
     * 
     * The database column names are used as the names of the child nodes. The names
     * are passed to the protected checkValidName() method to make sure they conform
     * to the XML tag naming conventions. If a column name is rejected, create an alias
     * in the SQL passed to the setQuery() method.
     *
     * @return int|string  If XML written to file, returns number of bytes; otherwise returns XML as string.
     */
    public function generateXML()
    {
        // Step 1: Check that the SQL query has been defined
        if (!isset($this->_sql)) {
            throw new LogicException('No SQL query defined! Use setQuery() ~CCC
before calling generateXML().');
        }
        // Submit the query to the database
        $resultSet = $this->_dbLink->getResultSet($this->_sql);
        // Step 2: Check first row of result for valid field names
        foreach (new LimitIterator($resultSet, 0, 1) as $row) {
            foreach ($row as $field => $value) {
                $this->checkValidName($field);
            }
        }
        // Step 3: Set root and top-level node names
        $this->_docRoot = isset($this->_docRoot) ? $this->_docRoot : 'root';
        $this->_element = isset($this->_element) ? $this->_element : 'row';
        // Step 4: Set a Boolean flag to insert primary key as attribute
        $usePK = (isset($this->_primaryKey) && !empty($this->_primaryKey));
        // Step 5: Generate and output the XML
        // Instantiate an XMLWriter object
        $xml = new XMLWriter();
        // Set the output preferences
        if (isset($this->_xmlFile)) {
            // Open the output file
            $fileOpen = @$xml->openUri($this->_xmlFile);
            if (!$fileOpen) {
                throw new RuntimeException("Cannot create $this->_xmlFile. Check ~CCC
      permissions and that target folder exists.");
            } else {
                // Set indentation preferences
                $xml->setIndent($this->_indent);
                $xml->setIndentString($this->_indentString);
            }
        } else {
            // If the output is being sent to a string, open memory instead
            $xml->openMemory();
        }
        // Start the document and create the root element
        $xml->startDocument();
        $xml->startElement($this->_docRoot);
        // Loop through each row of the database result set
        foreach ($resultSet as $row) {
            // Create the opening tag of the top-level node
            $xml->startElement($this->_element);
            // Add the primary key(s) as attribute(s)
            if ($usePK) {
                foreach ($this->_primaryKey as $pk) {
                    $xml->writeAttribute($pk, $row[$pk]);
                }
            }
            // Inside each row, loop through each field
            foreach ($row as $field => $value) {
                // Skip the primary key(s) if used as attribute(s) 
                if ($usePK && in_array($field, $this->_primaryKey)) {
                    continue;
                }
                // Create a child node for each field
                $xml->writeElement($field, $value);
            }
            // Create the closing tag for the top-level node
            $xml->endElement();
        }
        // Create the closing tag for the root element
        $xml->endElement();
        // Close the XML document
        $xml->endDocument();
        // Output the generated XML
        return $xml->flush();
    }

    /**
     * Checks that names conform to the XML tag naming conventions.
     *
     * @param string $name  Name to be checked.
     * @return string       Returns the name if valid; otherwise throws an exception.
     */
    protected function checkValidName($name)
    {
        if (preg_match('/^[\d\.-]/', $name)) {
            throw new RuntimeException('XML names cannot begin with a number, period, or hyphen.');
        }
        if (preg_match('/^xml/i', $name)) {
            throw new RuntimeException('XML names cannot begin with "xml".');
        }
        if (preg_match('/[\x00-\x2c\x2f\x3b-\x40\x5b-\x5e\x60\x7b-\xbf]/', $name)) {
            throw new RuntimeException('XML names cannot contain spaces or punctuation.');
        }
        if (preg_match('/:/', $name)) {
            throw new RuntimeException('Colons are permitted only in a namespace prefix. Pos_XmlExporter does not support namespaces.');
        }
        return $name;
    }
}
