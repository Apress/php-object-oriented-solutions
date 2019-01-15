<?php
/**
 * A class for validating and filtering user input
 * 
 * This class acts as a wrapper for the filter functions in >= PHP 5.2 or PECL.
 * If an optional array of required items is passed to the class constructor, 
 * the Pos_Validator object generates an array of missing items. 
 * 
 * The first argument for each validation method is the name of the input
 * field or URL variable, which is always required. Many methods accept
 * optional extra arguments, for example to set a range of acceptable
 * numbers, or to toggle between filtering single items (scalar values)
 * and arrays. Apart from checkTextLength(), only one test can be applied
 * to each field. If multiple tests are applied, the class throws an
 * exception identifying duplicates.
 * 
 * The validateInput() method returns an array of filtered input. It also generates
 * an array containing the names of fields that fail validation. A noFilter() method
 * handles fields that do not require filtering or that require special treatment. 
 * 
 * The PHP filter functions automatically strip "magic quotes" from form and
 * URL values, so filtered values need to be handled appropriately before display
 * in a web page or insertion into a database (for example, by using htmlentities()
 * or mysql_real_escape_string()).
 * 
 * Separate instances of the validator need to be created for the $_POST and $_GET
 * arrays, since the PHP filter functions cannot yet handle the $_REQUEST array.
 * 
 * @package Pos
 * @author David Powers
 * @copyright David Powers 2008
 * @version 1.0.1
 */
class Pos_Validator
{
    /**
     * Stores the source of input variables as a filter constant (INPUT_POST or INPUT_GET).
     *
     * @var int
     */
    protected $_inputType;
    /**#@+
     *
     * @var array
     */
    /**
     * Stores the raw input array.
     */
    protected $_submitted;
    /**
     * Stores an array of required fields.
     */
    protected $_required;
    /**
     * Stores the filtered output.
     */
    protected $_filtered;
    /**
     * Stores an indexed array of required fields that have not been filled in.
     */
    protected $_missing;
    /**
     * Stores an associative array of error messages.
     * 
     * The key (index) of each array element is the name of the field that failed validation.
     */
    protected $_errors;
    /**
     * Stores a multidimensional array of filter constants and flags to be applied to each field.
     */
    protected $_filterArgs;
    /**
     * Stores an array of fields that are being checked for a Boolean value (true or false).
     */
    protected $_booleans;

    /**#@-*/
    /**
     * Constructs a validator object for $_POST or $_GET input.
     * 
     * The constructor checks the availability of the PHP filter functions for which it
     * acts as a wrapper. If the optional first argument (an array of required fields) is 
     * supplied, it checks that each one contains at least some input. Any missing items are
     * stored as an array in the $missing property.
     * 
     * By default, the constructor sets the input type to "post". To create a validator for
     * the $_GET array, set the optional second argument to "get".
     * 
     * @param array  $required   Optional array containing the names of required fields or URL variables.
     * @param string $inputType  Type of input; valid options: "post" and "get" (case-insensitive); defaults to "post"
     * @return Pos_Validator object
     */
    public function __construct($required = array(), $inputType = 'post')
    {
        if (!function_exists('filter_list')) {
            throw new Exception('The Pos_Validator class requires the Filter Functions in >= PHP 5.2 or PECL.');
        }
        if (!is_null($required) && !is_array($required)) {
            throw new Exception('The names of required fields must be an array, even if only one field is required.');
        }
        $this->_required = $required;
        $this->setInputType($inputType);
        if ($this->_required) {
            $this->checkRequired();
        }
        $this->_filterArgs = array();
        $this->_errors = array();
        $this->_booleans = array();
    }

    ########################################################################
    # PUBLIC METHODS -- VALIDATION TESTS                                   #
    ########################################################################
    /**
     * Checks whether the submitted value is an integer.
     * 
     * If supplied, the optional second and third aguments set the minimum 
     * and/or maximum acceptable value. To set a maximum without setting a
     * minimum, the second argument must be explicitly set to null.
     * 
     * @param string  $fieldName  Name of submitted value to be checked.
     * @param int     $min        Optional minimum acceptable number.
     * @param int     $max        Optional maximum acceptable number.
     */
    public function isInt($fieldName, $min = null, $max = null)
    {
        // Check that another validation test has not been applied to the same input
        $this->checkDuplicateFilter($fieldName);
        // Set the filter options
        $this->_filterArgs[$fieldName] = array('filter' => FILTER_VALIDATE_INT);
        // Add filter options to test for integers within a specified range
        if (is_int($min)) {
            $this->_filterArgs[$fieldName]['options']['min_range'] = $min;
        }
        if (is_int($max)) {
            $this->_filterArgs[$fieldName]['options']['max_range'] = $max;
        }
    }

    /**
     * Checks whether the submitted value is a floating point number (with or without
     * a decimal fraction).
     * 
     * The optional second and third arguments set the decimal point to a period 
     * and permit the use of commas as the thousands separator. To use the comma 
     * as the decimal point, supply a comma in quotes as the second argument.
     * This automatically changes the thousands separator to a period.
     * Regardless of the type of decimal point selected, the filtered output strips
     * the thousands separator, and converts the decimal point to a period.
     * 
     * To reject numbers that contain a thousands separator, set the third argument to false.
     * When doing so, the second argument must be supplied, even if you want to use the
     * default period as the decimal point.
     * 
     * @param string   $fieldName               Name of submitted value to be checked. 
     * @param string   $decimalPoint            Optional character to be used as decimal point; default is a period.
     * @param boolean  $allowThousandSeparator  Optional; default setting accepts numbers with thousands separators.
     */
    public function isFloat($fieldName, $decimalPoint = '.', $allowThousandSeparator = true)
    {
        if ($decimalPoint != '.' && $decimalPoint != ',') {
            throw new Exception('Decimal point must be a comma or period in isFloat().');
        }
        // Check that another validation test has not been applied to the same input
        $this->checkDuplicateFilter($fieldName);
        // Set the filter options
        $this->_filterArgs[$fieldName] = array('filter'  => FILTER_VALIDATE_FLOAT,
                                               'options' => array('decimal' => $decimalPoint));
        if ($allowThousandSeparator) {
            $this->_filterArgs[$fieldName]['flags'] = FILTER_FLAG_ALLOW_THOUSAND;
        }
    }

    /**
     * Tests an array to ensure that all values are numeric.
     * 
     * Since floating point numbers don't necessarily need to have a decimal fraction, 
     * this method tests by default for floating point numbers. To test for integers,
     * set the optional second argument to false. The third and fourth optional 
     * arguments set the decimal point and thousands separator in the same way as
     * the isFloat() method.
     * 
     * @param string  $fieldName               Name of submitted value to be checked. 
     * @param boolean $allowDecimalFractions   Optional; default setting retains decimal fractions.
     * @param string  $decimalPoint            Optional character to be used as decimal point; default is a period.
     * @param boolean $allowThousandSeparator  Optional; default setting accepts numbers with thousands separators. 
     */
    public function isNumericArray($fieldName, $allowDecimalFractions = true, $decimalPoint = '.', $allowThousandSeparator = true)
    {
        if ($decimalPoint != '.' && $decimalPoint != ',') {
            throw new Exception('Decimal point must be a comma or period in isNumericArray().');
        }
        // Check that another validation test has not been applied to the same input
        $this->checkDuplicateFilter($fieldName);
        // Set the filter options 
        $this->_filterArgs[$fieldName] = array('filter'  => FILTER_VALIDATE_FLOAT,
                                               'flags'   => FILTER_REQUIRE_ARRAY,
                                               'options' => array('decimal' => $decimalPoint));
        // Additional flags are added using the |= "binary or" combined assignment operator
        if ($allowDecimalFractions) {
            $this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_ALLOW_FRACTION;
        }
        if ($allowThousandSeparator) {
            $this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_ALLOW_THOUSAND;
        }
    }

    /**
     * Checks whether the input conforms to the format of an email address.
     * 
     * It does not check whether the address is genuine. Multiple addresses are
     * rejected, guarding against email header injection attacks.
     * 
     * @param string $fieldName Name of submitted value to be checked.
     */
    public function isEmail($fieldName)
    {
        // Check that another validation test has not been applied to the same input
        $this->checkDuplicateFilter($fieldName);
        // Set the filter options 
        $this->_filterArgs[$fieldName] = FILTER_VALIDATE_EMAIL;
    }

    /**
     * Checks that the input conforms to the format of a full URL, including
     * scheme (such as http://).
     * 
     * The optional second parameter can be set to true if the URL needs
     * to contain a query string.
     * 
     * @param string   $fieldName            Name of submitted value to be checked.
     * @param boolean  $queryStringRequired  Optional; set to true if query string required; defaults to false.
     */
    public function isFullURL($fieldName, $queryStringRequired = false)
    {
        // Check that another validation test has not been applied to the same input
        $this->checkDuplicateFilter($fieldName);
        // Set the filter options 
        // Multiple flags are set using the "binary or" operator
        $this->_filterArgs[$fieldName] = array('filter' => FILTER_VALIDATE_URL,
                                               'flags' => FILTER_FLAG_SCHEME_REQUIRED |
                                                          FILTER_FLAG_HOST_REQUIRED |
                                                          FILTER_FLAG_PATH_REQUIRED);
        if ($queryStringRequired) {
            $this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_QUERY_REQUIRED;
        }
    }

    /**
     * Validates URLs in a less strict fashion than isFullURL().
     * 
     * @param string  $fieldName  Name of submitted value to be checked.
     */
    public function isURL($fieldName, $queryStringRequired = false)
    {
        // Check that another validation test has not been applied to the same input
        $this->checkDuplicateFilter($fieldName);
        // Set the filter options 
        $this->_filterArgs[$fieldName]['filter'] = FILTER_VALIDATE_URL;
        if ($queryStringRequired) {
            $this->_filterArgs[$fieldName]['flags'] = FILTER_FLAG_QUERY_REQUIRED;
        }
    }

    /**
     * Checks whether the submitted value is a boolean.
     * 
     * The following are treated as TRUE: "1", "true", "on" and "yes".
     * Everything else is treated as FALSE.
     * 
     * The optional second argument applies a stricter interpretation of
     * FALSE, treating only "0", "false", "off", "no", and an empty string
     * as FALSE. Any non-boolean values are returned by the filter as NULL.
     * 
     * @param string  $fieldName      Name of submitted value to be checked.
     * @param boolean $nullOnFailure  Optional; if true, applies stricter interpretation of FALSE; defaults to false.
     */
    public function isBool($fieldName, $nullOnFailure = false)
    {
        // Check that another validation test has not been applied to the same input
        $this->checkDuplicateFilter($fieldName);
        // Add the name of the input field or URL variable to an array of boolean values
        // This is necessary to prevent a boolean false value being interpreted as an error
        $this->_booleans[] = $fieldName;
        // Set the filter options 
        $this->_filterArgs[$fieldName]['filter'] = FILTER_VALIDATE_BOOLEAN;
        if ($nullOnFailure) {
            $this->_filterArgs[$fieldName]['flags'] = FILTER_NULL_ON_FAILURE;
        }
    }

    /**
     * Matches the submitted value against a Perl-compatible regular expression.
     * 
     * @param string  $fieldName  Name of submitted value to be checked.
     * @param string  $pattern    Perl-compatible regular expression.
     */
    public function matches($fieldName, $pattern)
    {
        // Check that another validation test has not been applied to the same input
        $this->checkDuplicateFilter($fieldName);
        // Set the filter options 
        $this->_filterArgs[$fieldName] = array('filter' => FILTER_VALIDATE_REGEXP,
                                               'options' => array('regexp' => $pattern));
    }

    /**
     * Sanitizes a string by removing completely all tags (including PHP and HTML).
     * 
     * The second and third optional arguments determine whether the ampersand
     * and quotes are converted to numerical entitites. By default, ampersands are
     * not converted, but both double and single quotes are replaced by numerical
     * entities.
     * 
     * Arguments four to seven determine whether characters with an ASCII value less than 32 or
     * greater than 127 are encoded or stripped. By default, they are left untouched.  
     * 
     * @param string  $fieldName       Name of submitted value to be checked.
     * @param boolean $encodeAmp       Optional; converts & to &#38; if set to true; defaults to false.
     * @param boolean $preserveQuotes  Optional; preserves double and single quotes if true; defaults to false.
     * @param boolean $encodeLow       Optional; converts ASCII values below 32 to entities; defaults to false.
     * @param boolean $encodeHigh      Optional; converts ASCII values above 127 to entities; defaults to false.
     * @param boolean $stripLow        Optional; strips ASCII values below 32; defaults to false.
     * @param boolean $stripHigh       Optional; strips ASCII values above 127; defaults to false.
     */
    public function removeTags($fieldName, $encodeAmp = false, $preserveQuotes = false, $encodeLow = false, $encodeHigh = false, $stripLow = false, $stripHigh = false)
    {
        // Check that another validation test has not been applied to the same input
        $this->checkDuplicateFilter($fieldName);
        // Set the filter options 
        $this->_filterArgs[$fieldName]['filter'] = FILTER_SANITIZE_STRING;
        // Multiple flags are set using the "binary or" operator
        $this->_filterArgs[$fieldName]['flags'] = 0;
        if ($encodeAmp) {
            $this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_ENCODE_AMP;
        }
        if ($preserveQuotes) {
            $this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_NO_ENCODE_QUOTES;
        }
        if ($encodeLow) {
            $this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_ENCODE_LOW;
        }
        if ($encodeHigh) {
            $this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_ENCODE_HIGH;
        }
        if ($stripLow) {
            $this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_STRIP_LOW;
        }
        if ($stripHigh) {
            $this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_STRIP_HIGH;
        }
    }

    /**
     * Sanitizes array by removing completely all tags (including PHP and HTML).
     * 
     * The second and third optional arguments determine whether the ampersand
     * and quotes are converted to numerical entitites. By default, ampersands are
     * not converted, but both double and single quotes are replaced by numerical
     * entities.
     * 
     * Arguments four to seven determine whether characters with an ASCII value less than 32 or
     * greater than 127 are encoded or stripped. By default, they are left untouched.  
     * 
     * @param string  $fieldName       Name of submitted value to be checked.
     * @param boolean $encodeAmp       Optional; converts & to &#38; if set to true; defaults to false.
     * @param boolean $preserveQuotes  Optional; preserves double and single quotes if true; defaults to false.
     * @param boolean $encodeLow       Optional; converts ASCII values below 32 to entities; defaults to false.
     * @param boolean $encodeHigh      Optional; converts ASCII values above 127 to entities; defaults to false.
     * @param boolean $stripLow        Optional; strips ASCII values below 32; defaults to false.
     * @param boolean $stripHigh       Optional; strips ASCII values above 127; defaults to false.
     */
    public function removeTagsFromArray($fieldName, $encodeAmp = false, $preserveQuotes = false, $encodeLow = false, $encodeHigh = false, $stripLow = false, $stripHigh = false)
    {
        // Check that another validation test has not been applied to the same input
        $this->checkDuplicateFilter($fieldName);
        // Set the filter options 
        $this->_filterArgs[$fieldName]['filter'] = FILTER_SANITIZE_STRING;
        // Multiple flags are set using the "binary or" operator
        $this->_filterArgs[$fieldName]['flags'] = FILTER_REQUIRE_ARRAY;
        if ($encodeAmp) {
            $this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_ENCODE_AMP;
        }
        if ($preserveQuotes) {
            $this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_NO_ENCODE_QUOTES;
        }
        if ($encodeLow) {
            $this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_ENCODE_LOW;
        }
        if ($encodeHigh) {
            $this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_ENCODE_HIGH;
        }
        if ($stripLow) {
            $this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_STRIP_LOW;
        }
        if ($stripHigh) {
            $this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_STRIP_HIGH;
        }
    }

    /**
     * Sanitizes input by converting to numeric entities single and double quotes, <, >, &, and
     * characters with an ASCII value of less than 32.
     * 
     * Optional arguments accept an array, convert characters with an ASCII value greater than
     * 127, or strip characters with an ASCII value less than 32 or greater than 127.
     * 
     * @param string  $fieldName  Name of submitted value to be checked. 
     * @param boolean $isArray    Optional; validates an array of strings if true; defaults to false.
     * @param boolean $encodeHigh      Optional; converts ASCII values above 127 to entities; defaults to false.
     * @param boolean $stripLow        Optional; strips ASCII values below 32; defaults to false.
     * @param boolean $stripHigh       Optional; strips ASCII values above 127; defaults to false.
     */
    public function useEntities($fieldName, $isArray = false, $encodeHigh = false, $stripLow = false, $stripHigh = false)
    {
        // Check that another validation test has not been applied to the same input
        $this->checkDuplicateFilter($fieldName);
        // Set the filter options 
        $this->_filterArgs[$fieldName]['filter'] = FILTER_SANITIZE_SPECIAL_CHARS;
        $this->_filterArgs[$fieldName]['flags'] = 0;
        if ($isArray) {
            $this->_filterArgs[$fieldName]['flags'] |= FILTER_REQUIRE_ARRAY;
        }
        if ($encodeHigh) {
            $this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_ENCODE_HIGH;
        }
        if ($stripLow) {
            $this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_STRIP_LOW;
        }
        if ($stripHigh) {
            $this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_STRIP_HIGH;
        }
    }

    /**
     * Checks the number of characters in the submitted value.
     * 
     * The first two arguments are required: the name of the form field or URL variable,
     * and the minimum acceptable length. The optional third argument sets a maximum length.
     * To set a maximum with no minimum, set the second argument to 0.
     * 
     * This is the only test that can be applied to input in addition to another. It should
     * always be used in conjunction with one of the other tests, as it doesn't sanitize the
     * input or remove magic quotes. Since all input is regarded as strings, further tests
     * might be necessary if you want to limit input to a particular type.
     * 
     * If the submitted data falls outside the specified range, an error message is added to
     * the validator's $_errors property.
     * 
     * @param string  $fieldName  Name of submitted value to be checked
     * @param int     $min        Minimum number of characters expected
     * @param int     $max        Optional; sets the maximum number of characters permitted
     */
    public function checkTextLength($fieldName, $min, $max = null)
    {
        // Get the submitted value
        $text = trim($this->_submitted[$fieldName]);
        // Make sure it's a string
        if (!is_string($text)) {
            throw new Exception("The checkTextLength() method can only be applied to strings; $fieldName is the wrong data type.");
        }
        // Make sure the second argument is a number
        if (!is_numeric($min)) {
            throw new Exception("The checkTextLength() method expects a number as the second argument (field name: $fieldName)");
        }
        // If the string is shorter than the minimum, create error message
        if (strlen($text) < $min) {
            // Check whether a valid maximum value has been set
            if (is_numeric($max)) {
                $this->_errors[$fieldName] = ucfirst($fieldName) . " must be between $min and $max characters.";
            } else {
                $this->_errors[$fieldName] = ucfirst($fieldName) . " must be a minimum of $min characters.";
            }
        }
        // If a maximum has been set, and the string is too long, create error message
        if (is_numeric($max) && strlen($text) > $max) {
            if ($min == 0) {
                $this->_errors[$fieldName] = ucfirst($fieldName) . " must be no more than $max characters.";
            } else {
                $this->_errors[$fieldName] = ucfirst($fieldName) . " must be between $min and $max characters.";
            }
        }
    }

    /**
     * This passes through the raw data as submitted.
     * 
     * The only changes made are the removal of magic quotes and (if the optional
     * third argument is set to true) the encoding of ampersands as &#38;.
     * Use this method if none of the other methods is suitable.
     * From the security point of view, it is advisable to subject such data
     * to a custom function before use.
     * 
     * @param string  $fieldName  Name of submitted value to be checked.
     * @param boolean $isArray    Optional; handles an array of values if true; defaults to false.
     * @param boolean $encodeAmp  Optional; converts & to &#38; if set to true; defaults to false.
     */
    public function noFilter($fieldName, $isArray = false, $encodeAmp = false)
    {
        // Check that another validation test has not been applied to the same input
        $this->checkDuplicateFilter($fieldName);
        // Set the filter options 
        $this->_filterArgs[$fieldName]['filter'] = FILTER_UNSAFE_RAW;
        // Multiple flags are set using the "binary or" operator
        $this->_filterArgs[$fieldName]['flags'] = 0;
        if ($isArray) {
            $this->_filterArgs[$fieldName]['flags'] |= FILTER_REQUIRE_ARRAY;
        }
        if ($encodeAmp) {
            $this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_ENCODE_AMP;
        }
    }

    ##################################################################################
    # PUBLIC METHODS -- VALIDATION AND RESULTS                                       #
    ##################################################################################
    /**
     * Performs the actual validation and filtering of input.
     * 
     * This method checks that a validation test has been applied to each required
     * input field or URL variable, and throws an exception if any required data has
     * not been filtered, so it must come after the individual tests have been applied.
     * 
     * It returns an array of filtered data, which should be used instead
     * of the raw data from the $_POST or $_GET array. It also creates an array of
     * any errors encountered in the validation process.
     * 
     * @return array Associative array of filtered input data
     */
    public function validateInput()
    {
        // Initialize an array for required items that haven't been validated
        $notFiltered = array();
        // Get the names of all fields that have been validated
        $tested = array_keys($this->_filterArgs);
        // Loop through the required fields
        // Add any missing ones to the $notFiltered array
        foreach ($this->_required as $field) {
            if (!in_array($field, $tested)) {
                $notFiltered[] = $field;
            }
        }
        // If any items have been added to the $notFiltered array, it means a
        // required item hasn't been validated, so throw an exception
        if ($notFiltered) {
            throw new Exception('No filter has been set for the following required item(s): ' . implode(',', $notFiltered));
        }
        // Apply the validation tests using filter_input_array()
        $this->_filtered = filter_input_array($this->_inputType, $this->_filterArgs);
        foreach ($this->_filtered as $key => $value) {
            // Skip items that used the isBool() method or that are either missing or not required
            if (in_array($key, $this->_booleans) || in_array($key, $this->_missing) || !in_array($key, $this->_required)) {
                continue;
            } elseif ($value === false) {
                // If the filtered value is a boolean false, it failed validation,
                // so add it to the $errors array
                $this->_errors[$key] = ucfirst($key) . ': invalid data supplied';
            }
        }
        // Return the validated input as an array
        return $this->_filtered;
    }

    /**
     * Returns an array of required items that have not been filled in
     * 
     * @return array Indexed array of names of missing fields or variables.
     */
    public function getMissing()
    {
        return $this->_missing;
    }

    /**
     * Returns an array of the filtered data after validation.
     * 
     * This is provided as a way of accessing the validated input without
     * the need to process it again.
     * 
     * @return array Multidimensional associative array of field (variable) names and filtered values
     */
    public function getFiltered()
    {
        return $this->_filtered;
    }

    /**
     * Returns an array containing the names of fields or variables that failed
     * the validation test.
     * 
     * @return array  Indexed array of fields (variables) that failed validation
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    ############################################################################
    # PROTECTED METHODS                                                        #
    ############################################################################
    /**
     * Checks the input type, and assigns the appropriate superglobal array to the submitted property.
     * 
     * Uses the PHP constants defined by the filter functions. 
     * 
     * @param string  $type  Specifies the input type to be processed; valid values: "post" and "get" (case-insensitive)     
     */
    protected function setInputType($type)
    {
        switch (strtolower($type)) {
            case 'post':
                $this->_inputType = INPUT_POST;
                $this->_submitted = $_POST;
                break;
            case 'get':
                $this->_inputType = INPUT_GET;
                $this->_submitted = $_GET;
                break;
            default:
                throw new Exception('Invalid input type. Valid types are "post" and "get".');
        }
    }

    /**
     * Checks the submitted value of all required items to ensure that the field isn't 
     * blank.
     * 
     * If the item is a scalar (single) value, whitespace is stripped from both
     * ends to prevent users from entering a series of spaces. Populates the $_missing
     * property with the names of missing fields or variables. 
     */
    protected function checkRequired()
    {
        $OK = array();
        foreach ($this->_submitted as $name => $value) {
			$value = is_array($value) ? $value : trim($value);
			if (!empty($value)) {
				$OK[] = $name;
			}
        }
        $this->_missing = array_diff($this->_required, $OK);
    }

    /**
     * Throws an exception if more than one validation test is applied to the current item.
     * 
     * @param string  $fieldName  Name of field being validated
     */
    protected function checkDuplicateFilter($fieldName)
    {
        if (isset($this->_filterArgs[$fieldName])) {
            throw new Exception("A filter has already been set for the following field: $fieldName.");
        }
    }
}

