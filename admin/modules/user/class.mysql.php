<?php
// ----------------------------------------------------------------------
// Smart (PHP Framework)
// Copyright (c) 2004
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/*
 * The SqLite Class
 *
 */

class DB
{
    /**
     * Resouce of SQLite connection
     *
     * @var     resource
     * @access  private
     * @see     SPSQLite()
     */

    var $_conn = null;
    /**
     * The SQL query
     *
     * @var     string
     * @access  private
     * @see     query()
     */

    var $_command = '';

    /**
     * Use or not use buffer/unbeffered result?
     *
     * @var     bool
     * @access  private
     * @see     query()
     */

    var $_buffer = true;

    /**
     * Set behaviour: reset or not reset the type setting?
     * If $_rememberType is true, it comes remembered between one call and the
     * other of returnRow() and selectRow(), if it is false, $_type always comes
     * set on 'both'
     *
     * @var     string
     * @access  private
     * @see     SPSQLite(), returnRows(), selectRows()
     */

    var $_rememberType = true;

    /**
     * Type of array index (row)
     *
     * @var     string  $_type
     * @access  private
     * @see     setType(), returnRows(), selectRows()
     */
    
    var $_type = 'ASSOC';

    /**
     * Array with table informations
     *
     * @var     array
     * @access  private
     * @see     
     */

    var $_tableInfo = '';

    /**
     * Array with columns names and columns types
     *
     * @var     array
     * @access  private
     * @see     
     */

    var $_colsType = '';
    
    /**
     * Array with error messages
     *
     * @var     array
     * @access  private   
     */

    var $_error = FALSE;
    

    // }}}
    
    // {{{ constructor SPSQLite()

    /**
    * Set the properties $file, $persistent.
    * Connect to database.
    *
    * @param    string  $file           filename (the SQLite database)
    * @param    bool    $persistent     true or false
    * @param    bool    $showError      true or false
    * @param    bool    $rememberType   true or false
    * @access   public
    * @return   void
    */

    function DB($host, $user, $passwd, $db_name = FALSE, $persistent = FALSE)
    {   
        if (FALSE == $persistent) {
            $this->_conn = @mysql_connect($host,$user,$passwd);
        } else {
            $this->_conn = @mysql_pconnect($host,$user,$passwd);
        }
        if (!is_resource($this->_conn)) {
            trigger_error("Impossible to connect to the databasehost \n$host\nFILE: " . __FILE__ ."\nLINE:". __LINE__, E_USER_ERROR);
        }
        
        $this->_row_types = array('ASSOC' => MYSQL_ASSOC,
                                  'NUM'   => MYSQL_NUM,
                                  'BOTH'  => MYSQL_BOTH); 
                                  
        if (!empty($db_name))
        {
            @mysql_select_db($db_name, $this->_conn);        
        }
    }

    function select_db ( $db_name )
    {
        if (FALSE === @mysql_select_db($db_name, $this->_conn))
        {
            trigger_error("Impossible to connect to the database \n$db_name\nFILE: " . __FILE__ ."\nLINE:". __LINE__, E_USER_ERROR);        
        }    
    }

    // }}}

    // {{{ otimizeDatabase()
    
    /**
    * Optimize dimension of database with the SQLite statemant VACUUM
    *
    * @param    string      $indexOrTable   an index of a name of table
    * @access   public
    * @return   void
    */
    
    function optimizeDatabase($indexOrTable)
    {
        $this->query("OPTIMIZE " . $indexOrTable);
    }

    // }}}

    // {{{ query()
    
    /**
    * Submit a SQL query to database
    *
    * @param    string  $query      query SQL SQLite compatible     
    * @param    bool    $buffer     true or false (If you only need sequential access to
                                    the data, it is recommended false)
                                    If you use false, some function do not work.
    * @access   public
    * @return   bool
    */

    function query($query, $buffer = true)
    {        
        $result = @mysql_query( $query , $this->_conn );
        
        if (!$result) {
            $this->_error = 'SQL-ERROR: '.chr(10).mysql_error().chr(10).'SQL_ERROR_NO: '.chr(10).mysql_errno();
            return FALSE;
        } else {
            return $result;
        }
    }

    /**
    * This method set a type-index of the array rows
    *
    * @param    string      $type   'both', 'num' or 'assoc'
    * @access   private
    * @return   void
    */
    
    function setType($type)
    {
        if ($type == 'both' || (empty($type) && !$this->_rememberType)) {
            $this->_type = 'BOTH';
        }
        if ($type == 'assoc') {
            $this->_type = 'ASSOC';
        }
        if ($type == 'num') {
            $this->_type = 'NUM';
        }
    }

    // }}}

    /**
    * Get rows !!
    *
    * @param    string  $type       'BOTH', 'ASSOC' or 'NUM'
    * @access   public
    * @return   mixed
    */
    
    function getRow($result, $type = null)
    {
        if (isset($type)) {
            $this->setType($type);
        }
        return @mysql_fetch_array($result, $this->_row_types[$this->_type]);
    }
    
    // }}}
    
    // }}}
    
    // {{{ lastInsertId()
    
    /**
    * Return the last insert id (column declared INTEGER PRIMARY KEY )
    *
    * @access public
    * @return int
    */
    
    function lastInsertId()
    {
        $sql = "SELECT LAST_INSERT_ID() AS id";
        $result = $this->query($sql);
        $result = $this->getRow($result);
        return $result['id'];    
    }
    
    // }}}

    // {{{ affectedRows()
    
    /**
    * Return how many lines are changed
    *
    * @access public
    * @return int
    */
    
    function affectedRows()
    {
        return mysql_affected_rows();
    }
    
    // }}}

    // {{{ numRows()
    
    /**
    * Return the number of rows
    *
    * @access public
    * @return int
    */
    
    function numRows($result)
    {
       return @mysql_num_rows( $result );    
    }
    
    // }}}

    // {{{ escapeString()
    
    /**
    * Prepare a string with special characters
    *
    * @param  string $string
    * @access public
    * @return string
    */
    
    function escapeString($string)
    {
        return addslashes($string);
    }
    
    // }}}

    // {{{ libEncoding()
    
    /**
    * The encoding of library
    *
    * @access public
    * @return string
    */
    
    function libEncoding()
    {
        return FALSE;
    }
    
    // }}}

    // {{{ libVersion()
    
    /**
    * The version of library
    *
    * @access public
    * @return string
    */
    
    function libVersion()
    {
        return FALSE;
    }
    
    // }}}

    // {{{ close()
    
    /**
    * Close a connection to database
    *
    * @access public
    * @return void
    */
    
    function close()
    {
        @mysql_close($this->_conn);
    }

    // }}}

    // {{{ _filterName()
    
    /**
    * Accessory method
    *
    * @access private
    * @return string
    */
    
    function _filterName($col)
    {
        $pos = strpos($col, ' ');
        /* see str_split() in PHP 5 */
        $name = substr($col, 0, $pos);

        return trim($name);
    }

    // }}}

    // {{{ _filterType()

    /**
    * Accessory method
    *
    * @access private
    * @return string
    */

    function _filterType($col)
    {
        $n = strpos($col, ' ');
        /* see str_split() in PHP 5 */
        $type = substr($col, $n + 1, strlen($col));

        return trim($type);
    }

    // }}}

    // {{{ _filterTrim()

    /**
    * Accessory method
    *
    * @access private
    * @return string
    */

    function _filterTrim($col)
    {
        return trim($col);
    }

    // }}}

    // {{{ _filterName()

    /**
    * Accessory method
    *
    * @access private
    * @return array
    */

    /* see array_combine in PHP 5 */
    function _colsTypeCombine($name, $type)
    {
        for ($i = 0; $i < count($name); $i++) {
            $cols[$name[$i]] = $type[$i];
        }
        return $cols;
    }

    /**
    * Get error message
    *
    * @access   public
    * @return   string
    */
    function get_error()
    {
        $get = $this->_error;
        // Clear error string
        $this->_error = FALSE;
        return $get;
    }  
}

    // }}}


?>
