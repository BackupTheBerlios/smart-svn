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
 * SYSTEM_SYS_PREPEND class - Run code before the application logic
 *
 */
 
class SYSTEM_SYS_GET_PUBLIC_VIEW
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
    function SYSTEM_SYS_GET_PUBLIC_VIEW()
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
     * Run filters and other stuff before the application logic  
     *
     *
     * @param array $data
     */
    function perform( $data )
    {
        // If no template request is done load the default template
        if (!isset($_REQUEST['tpl']))
        {
            $tpl = 'index';
        }
        elseif(!preg_match("/[a-z_]{1,30}/", $_REQUEST['tpl']))
        {
            trigger_error( "WRONG VAR FORMAT: tpl\nVALUE: " . $_REQUEST['tpl'] . "\nFILE: " . __FILE__ . "\nLINE:" . __LINE__  );    
        }   

        // build the whole requested template file path
        $this->B->template_file = SF_BASE_DIR . $B->sys['option']['tpl'] . '_' . $tpl . '.tpl.php';

        // check if the requested template exist
        if (!@file_exists( $B->template_file ))
        {
            // on error
            die ("The requested template file '{$B->template_file}' dosent exist! Please contact the administrator {$B->sys['option']['email']}");
        }
        
        return $this->B->template_file;
    }    
}

?>
