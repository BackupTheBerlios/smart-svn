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

/**
 * custom sfErrorManager handler for the callback error handling mode
 *
 * @package     default
 * @author      Armand Turpel <smart@open-publisher.net>
 */
class sfErrorHandler
{
   /**
    * constructor php4
    * set php error handler callback function
    */
    function sfErrorHandler()
    {
        $this->__construct();    
    }
    
   /**
    * constructor php5
    * set php error handler callback function
    */    
    function __construct()
    {
        set_error_handler(array( &$this, '_php_error_handler' ));
    }

   /**
    * php error handler
    *
    * @access privat
    */    
    function _php_error_handler( $errno, $errstr, $errfile, $errline )
    {
        if ((SF_ERROR_REPORTING & $errno) == 0)
        {
            return;
        }
        
        $errortype = array (
               E_ERROR           => "E_ERROR",
               E_WARNING         => "E_WARNING",
               E_PARSE           => "E_PARSE",
               E_NOTICE          => "E_NOTICE",
               E_CORE_ERROR      => "E_CORE_ERROR",
               E_CORE_WARNING    => "E_CORE_WARNING",
               E_COMPILE_ERROR   => "E_COMPILE_ERROR",
               E_COMPILE_WARNING => "E_COMPILE_WARNING",
               E_USER_ERROR      => "E_USER_ERROR",
               E_USER_WARNING    => "E_USER_WARNING",
               E_USER_NOTICE     => "E_USER_NOTICE",
               E_STRICT          => "E_STRICT"
               );
        // set of errors for which a var trace will be saved
        //$user_errors = array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE);
  
        $err  = "\nDATETIME: " . date("Y-m-d H:i:s", time()) . "\n";
        $err .= "\nPHP_ERRNO: " . $errno . "\n";
        $err .= "PHP_ERROR_TYPE: " . $errortype[$errno] . "\n";
        $err .= "PHP_ERROR_FILE: " . $errfile . "\n";
        $err .= "PHP_ERROR_LINE: " . $errline . "\n";
        $err .= "PHP_ERROR_MESSAGE: " . $errstr . "\n";

        $this->_log( $err );
    }   
    
   /**
    * logging
    *
    * @param string $error
    * @param string $error_base
    * @access privat
    */     
    function _log( &$error )
    {
        // Log this error to file
        if(strstr(SF_ERROR_HANDLE, 'LOG'))
        {
            error_log($error."\n\n", 3, SF_BASE_DIR . '/admin/logs/error.log');
        }  
        // Print this error
        if(strstr(SF_ERROR_HANDLE, 'SHOW'))
        {
            echo '<div style="backgound-color:#ffffff;">'.nl2br($error).'</div><br />';
        }         
    }
}
?>