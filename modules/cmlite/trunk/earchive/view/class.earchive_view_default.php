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
 * earchive_view_default class of the template "default.tpl.php"
 *
 */
 
class earchive_view_default
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
    function earchive_view_default()
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
     * Execute the view of the template "default.tpl.php"
     *
     * @return bool true on success else false
     */
    function perform()
    {
        $this->B->tmp_fields = array('lid','status','email','name','description');
        $this->B->all_lists = $this->B->earchive->get_lists( $this->B->tmp_fields );
        
        return TRUE;
    }    
}

?>
