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
        // check permission to access this module
        if( FALSE == $this->B->M( MOD_EARCHIVE, 'filter_permission', array('action' => 'access')))
        {
            @header('Location: '.SF_BASE_LOCATION.'/index.php?admin=1');
            exit;      
        }
    
        // set default sub section
        if(!isset($_REQUEST['sec']) || empty($_REQUEST['sec'])) 
        {
            $_REQUEST['sec'] = 'default';
        }
        
        return TRUE;
    }    
}

?>
