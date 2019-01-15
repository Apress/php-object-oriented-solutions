<?php
/**
 * A class for retrieving the content of a non-binary remote file.
 * 
 * The class automatically detects if allow_url_fopen is disabled on the
 * local server, and uses cURL (if available) or a socket connection
 * instead. It takes a single argument: the URL of the remote file.
 * 
 * The class implements the __toString() method, so the object can
 * be used directly in any string context. If the connection to the
 * remote server fails, the class returns an empty string. The
 * getErrorMessage() public method returns an error message
 * 
 * @package Pos
 * @author David Powers
 * @copyright David Powers 2008
 * @version 1.0.1
 */
class Pos_RemoteConnector
{
    /**#@+
     *
     * @var string
     */
    /**
     * The URL of the remote file to be retrieved.
     */
    protected $_url;
    /**
     * The output returned by the remote server.
     */
    protected $_remoteFile;
    /**
     * An error message based on the HTTP status code.
     */
    protected $_error;
    /**
     * The HTTP status code returned by the remote server.
     */
    protected $_status;
    /**#@-*/
    /**
     * The constituent parts of the URL (host, path, etc).
     *
     * @var array
     */
    protected $_urlParts;

    /**
     * Constructor.
     *
     * The constructor takes the URL of the remote file to be retrieved, checks
     * that it is a valid URL format, and selects the method of connection to
     * the remote server depending on the configuration of the server
     * initiating the request.
     * 
     * If allow_url_fopen is enabled, it invokes the accessDirect() method,
     * which uses file_get_contents().
     * 
     * If allow_url_fopen is disabled, but the cURL extension is available, the 
     * useCurl() method is invoked instead.
     * 
     * If neither allow_url_fopen nor cURL is available, the constructor
     * invokes the useSocket() method to establish a socket connection.
     * 
     * @param string $url
     */
    public function __construct($url)
    {
        $this->_url = $url;
        $this->checkURL();
        if (ini_get('allow_url_fopen')) {
            $this->accessDirect();
        } elseif (function_exists('curl_init')) {
            $this->useCurl();
        } else {
            $this->useSocket();
        }
    }

    ##################
    # PUBLIC METHODS #
    ##################
    /**
     * Returns the contents of the remote file or an empty string.
     * 
     * If the connection to the remote server failed, the $_remoteFile
     * property is a Boolean false, so must be converted to an empty
     * string for the __toString() magic method to work.
     *
     * @return string Contents of the remote file on success, or a blank string on failure.
     */
    public function __toString()
    {
        if (!$this->_remoteFile) {
            $this->_remoteFile = '';
        }
        return $this->_remoteFile;
    }

    /**
     * Returns an error message based on the HTTP status code.
     * 
     * Calls the protected setErrorMessage() method if the $_error property
     * hasn't been set.
     *
     * @return string Error message based on HTTP status code.
     */
    public function getErrorMessage()
    {
        if (is_null($this->_error)) {
            $this->setErrorMessage();
        }
        return $this->_error;
    }

    #####################
    # PROTECTED METHODS #
    #####################
    /**
     * Checks that the URL is correctly formatted, and populates the $_urlParts property.
     * 
     * Accepts only URLs that begin with http.
     * 
     * Uses filter_var() with the FILTER_VALIDATE_URL filter, together with flags
     * requiring a URL scheme and host. The filter fails to detect incorrectly formed
     * hosts, so a Perl-compatible regular expression is used to make sure the
     * host doesn't begin with a period, and contains a period followed by at least
     * two characters. 
     */
    protected function checkURL()
    {
        // Use filter_var() to validate the URL
        $flags = FILTER_FLAG_SCHEME_REQUIRED | FILTER_FLAG_HOST_REQUIRED;
        $urlOK = filter_var($this->_url, FILTER_VALIDATE_URL, $flags);
        // Populate the $_urlParts property
        $this->_urlParts = parse_url($this->_url);
        // Use a PCRE to make sure domain contains at least one period
        $domainOK = preg_match('/^[^.]+?\.\w{2}/', $this->_urlParts['host']);
        if (!$urlOK || $this->_urlParts['scheme'] != 'http' || !$domainOK) {
            throw new Exception($this->_url . ' is not a valid URL');
        }
    }

    /**
     * Retrieves remote file and HTTP status code if allow_url_fopen is enabled.
     * 
     * Uses file_get_contents() and get_headers().
     */
    protected function accessDirect()
    {
        // Get the contents of the file
        $this->_remoteFile = @ file_get_contents($this->_url);
        // Get the headers as an array
        $headers = @ get_headers($this->_url);
        // If the headers are returned, use a PCRE to extract the status code
        // from the first header
        if ($headers) {
            preg_match('/\d{3}/', $headers[0], $m);
            $this->_status = $m[0];
        }
    }

    /**
     * Uses the cURL extension to retrieve the remote file and HTTP status code.
     */
    protected function useCurl()
    {
        if ($session = curl_init($this->_url)) {
            // Supress the HTTP headers
            curl_setopt($session, CURLOPT_HEADER, false);
            // Return the remote file as a string, rather than output it directly
            curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
            // Get the remote file and store it in the $remoteFile property
            $this->_remoteFile = curl_exec($session);
            // Get the HTTP status
            $this->_status = curl_getinfo($session, CURLINFO_HTTP_CODE);
            // Close the cURL session
            curl_close($session);
        } else {
            $this->_error = 'Cannot establish cURL session';
        }
    }

    /**
     * Uses a socket connection to retrieve the remote file and HTTP status code.
     * 
     * If a successful connection is made, calls the protected removeHeaders()
     * method to separate the headers from the body of the file.
     */
    protected function useSocket()
    {
        $port = isset($this->_urlParts['port']) ? $this->_urlParts['port'] : 80;
        $remote = @ fsockopen($this->_urlParts['host'], $port, $errno, $errstr, 30);
        if (!$remote) {
            $this->_remoteFile = false;
            $this->_error = "Couldn't create a socket connection: ";
            if ($errstr) {
                $this->_error .= $errstr;
            } else {
                $this->_error .= 'check the domain name or IP address.';
            }
        } else {
            // Add the query string to the path, if it exists
            if (isset($this->_urlParts['query'])) {
                $path = $this->_urlParts['path'] . '?' . $this->_urlParts['query'];
            } else {
                $path = $this->_urlParts['path'];
            }
            // Create the request headers
            $out = "GET $path HTTP/1.1\r\n";
            $out .= "Host: {$this->_urlParts['host']}\r\n";
            $out .= "Connection: Close\r\n\r\n";
            // Send the headers
            fwrite($remote, $out);
            // Capture the response
            $this->_remoteFile = stream_get_contents($remote);
            fclose($remote);
            if ($this->_remoteFile) {
                $this->removeHeaders();
            }
        }
    }

    /**
     * Removes headers, extracts HTTP status code, and cleans up the body
     * of the remote file if a socket connection is used.
     *
     * When using a socket connection, the headers and body of the file
     * are sent in a single stream. This method separates the headers
     * from the rest of the output, and extracts the HTTP status code
     * and Content-Type. If the Content-Type header indicates that
     * the file contains XML or HTML, a PCRE discards anything outside
     * the opening and closing angle brackers. Other file types are
     * left untouched apart from the removal of whitespace at the
     * beginning and end of the file. 
     */
    protected function removeHeaders()
    {
        // Split the output into an array on each blank line
        $parts = preg_split('#\r\n\r\n|\n\n#', $this->_remoteFile);
        if (is_array($parts)) {
            // If an array was created successfully, remove the first element
            // and store it as $headers.
            $headers = array_shift($parts);
            // Join the remaining elements back together with a blank line between each one
            $file = implode("\n\n", $parts);
            // Use a PCRE to extract the status code
            if (preg_match('#HTTP/1\.\d\s+(\d{3})#', $headers, $m)) {
                $this->_status = $m[1];
            }
            // Use a PCRE to extract the Content-type header
            if (preg_match('#Content-Type:([^\r\n]+)#i', $headers, $m)) {
                // If a Content-Type header is found, see if it includes "xml" or "html"
                if (stripos($m[1], 'xml') !== false || stripos($m[1], 'html') !== false) {
                    // If XML or HTML, discard anything outside the angle brackets
                    // Otherwise, just remove whitespace from the beginning and end
                    if (preg_match('/<.+>/s', $file, $m)) {
                        $this->_remoteFile = $m[0];
                    } else {
                        $this->_remoteFile = trim($file);
                    }
                } else {
                    // If no Content-Type header is found, just remove whitespace
                    $this->_remoteFile = trim($file);
                }
            }
        }
    }

    /**
     * Sets an error message based on the HTTP status code from the remote server.
     * 
     * Uses an empty string if the status code is 200 and the $_remoteFile property
     * contains something.
     */
    protected function setErrorMessage()
    {
        if ($this->_status == 200 && $this->_remoteFile) {
            $this->_error = '';
        } else {
            switch ($this->_status) {
                case 200:
                case 204:
                    $this->_error = 'Connection OK, but file is empty.';
                    break;
                case 301:
                case 302:
                case 303:
                case 307:
                case 410:
                    $this->_error = 'File has been moved or does not exist.';
                    break;
                case 305:
                    $this->_error = 'File must be accessed through a proxy.';
                    break;
                case 400:
                    $this->_error = 'Malformed request.';
                    break;
                case 401:
                case 403:
                    $this->_error = 'You are not authorized to access this page.';
                    break;
                case 404:
                    $this->_error = 'File not found.';
                    break;
                case 407:
                    $this->_error = 'Proxy requires authentication.';
                    break;
                case 408:
                    $this->_error = 'Request timed out.';
                    break;
                case 500:
                    $this->_error = 'The remote server encountered an internal error.';
                    break;
                case 503:
                    $this->_error = 'The server cannot handle the request at the moment.';
                    break;
                default:
                    $this->_error = 'Undefined error. Check URL and domain name.';
                    break;
            }
        }
    }
}

