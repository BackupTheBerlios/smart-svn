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
 * @access      public
 * @package     default
 */
 
/**
 * custom sfErrorManager handler for the callback error handling mode
 *
 * @package     default
 * @author      Armand Turpel <smart@open-publisher.net>
 */
class sfErrorHandler
{
   /**
    * constructor
    * set php error handler callback function
    */
    function sfErrorHandler()
    {
        set_error_handler(array( &$this, '_php_error_handler' ));
    }

   /**
    * error handler (from patErrorManager)
    *
    * @param    object      error object
    */
    function &sfDebug( &$error )
    {
        $err  = patErrorManager::translateErrorLevel( $error->getLevel() )."<br />";
        $err .= $error->getMessage()."<br />".$error->getInfo();
        
        $this->_log( &$err, 'SF:' );
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
               E_ERROR           => "Error",
               E_WARNING         => "Warning",
               E_PARSE           => "Parsing Error",
               E_NOTICE          => "Notice",
               E_CORE_ERROR      => "Core Error",
               E_CORE_WARNING    => "Core Warning",
               E_COMPILE_ERROR   => "Compile Error",
               E_COMPILE_WARNING => "Compile Warning",
               E_USER_ERROR      => "User Error",
               E_USER_WARNING    => "User Warning",
               E_USER_NOTICE     => "User Notice",
               E_STRICT          => "Runtime Notice"
               );
        // set of errors for which a var trace will be saved
        //$user_errors = array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE);
  
        $err  = "\nPHP_ERRNO: " . $errno . "\n";
        $err .= "PHP_ERROR_TYPE: " . $errortype[$errno] . "\n";
        $err .= "PHP_ERROR_FILE: " . $errfile . "\n";
        $err .= "PHP_ERROR_LINE: " . $errline . "\n";
        $err .= "PHP_ERROR_MESSAGE: " . $errstr . "\n";

        $this->_log( $err, 'PHP:' );
    }   
    
   /**
    * logging
    *
    * @param string $error
    * @param string $error_base
    * @access privat
    */     
    function _log( &$error, $error_base )
    {
        global $base;
        
        // Log this error to file
        if(strstr(SF_ERROR_HANDLE, 'LOG'))
        {
            $base->error_log = &Log::singleton('file', SF_BASE_DIR . '/admin/logs/error.log', $error_base);
            $base->error_log->log($error, LOG_INFO);
        }  
        // Print this error
        if(strstr(SF_ERROR_HANDLE, 'SHOW'))
        {
            echo '<div style="backgound-color:#ffffff;">'.nl2br($error).'</div><br />';
        }         
    }
}
?>