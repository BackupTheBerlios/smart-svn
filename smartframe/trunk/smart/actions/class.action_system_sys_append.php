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
 * action_system_sys_append class - Run code after the application logic
 *
 */
 
class action_system_sys_append extends action
{
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
        @ob_clean();

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
        $content = M( MOD_SYSTEM , 'trim_output', $content );

        // email obfuscation
        $content = M( MOD_SYSTEM , 'email_obfuscating', $content  );  
        
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
        $content = M( MOD_SYSTEM , 'trim_output', $content );
        return $content;
    }    
}

?>
