<?php
// ----------------------------------------------------------------------
// Smart PHP Framework
// Copyright (c) 2004
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * system_sys_append class - Run code after the application logic
 *
 */
 
class system_sys_append
{
    /**
     * Global system instance
     * @var object $B
     */
    var $B;
    
    /**
     * constructor
     *
     */
    function system_sys_append()
    {
        $this->__construct();
    }

    /**
     * constructor php5
     *
     */
    function __construct()
    {
        $this->B = & $GLOBALS['B'];
    }
    
    /**
     * Run filters and other stuff after the application logic  
     *
     * @param array $data
     * @return string filtered page content
     */
    function perform( $data )
    {
        // get the output buffer
        $content = ob_get_contents();
        ob_clean();

        switch( SF_SECTION )
        {
            case 'admin':
                // run filters for the admin page
                echo $this->_filter_admin( $content );
                break;
            default:
                // run filters for the public page
                echo $this->_filter_public( $content );
                break;            
        } 
    }  

    /**
     * Run public filters 
     *
     * @param string $content
     * @return string filtered page content
     * @access privat
     */    
    function _filter_public( & $content )
    {
        // Manual order the filter flow 
    
        // Remove space before and after the template output
        $content = F( SYSTEM_FILTER , 'trim_output', $content );

        // email obfuscation
        $content = F( SYSTEM_FILTER , 'email_obfuscating', $content  );  
        
        return $content;
    }

    /**
     * Run admin filters 
     *
     * @param string $content
     * @return string filtered page content
     * @access privat
     */    
    function _filter_admin( & $content )
    {
        // Manual order the filter flow 
    
        // Remove space before and after the template output
        $content = F( SYSTEM_FILTER , 'trim_output', $content );
        return $content;
    }    
}

?>
