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
 * earchive_view_index class of the template "index.tpl.php"
 *
 */

// earchive rights class
include_once(SF_BASE_DIR.'modules/earchive/includes/class.rights.php'); 

class earchive_view_index
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
    function earchive_view_index()
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
     * Execute the view of the template "index.tpl.php"
     *
     * @return bool true on success else false
     */
    function perform()
    {
        if(!isset($_REQUEST['sec']) || empty($_REQUEST['sec'])) 
        {
            $_REQUEST['sec'] = 'default';
        }

        if(!is_object($this->B->earchive_rights))
        {        
            $this->B->earchive_rights = & new earchive_rights;
        }
        // the user class
        include_once SF_BASE_DIR . 'modules/earchive/includes/class.earchive.php';

        if(!is_object($this->B->earchive))
        {
            //User Class instance
            $this->B->earchive = & new earchive;        
        }
        
        return TRUE;
    }    
}

?>
