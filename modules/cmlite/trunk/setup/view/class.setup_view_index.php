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
 * setup_view_index class 
 *
 */
 
class setup_view_index
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
    function setup_view_index()
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
     * Do setup for this module
     *
     * @param array $data
     */
    function perform( $data )
    {    
        // launch setup
        if( $_POST['do_setup'] )
        {
            if( FALSE == $this->B->M(MOD_SETUP, 'sys_setup') )
            {
                $this->B->form_host        = htmlspecialchars(commonUtil::stripSlashes($_POST['dbhost']));
                $this->B->form_user        = htmlspecialchars(commonUtil::stripSlashes($_POST['dbuser']));
                $this->B->form_dbname      = htmlspecialchars(commonUtil::stripSlashes($_POST['dbname']));
                $this->B->form_tableprefix = htmlspecialchars(commonUtil::stripSlashes($_POST['dbtablesprefix']));
                $this->B->form_sysname     = htmlspecialchars(commonUtil::stripSlashes($_POST['sysname']));
                $this->B->form_syslastname = htmlspecialchars(commonUtil::stripSlashes($_POST['syslastname']));
                $this->B->form_syslogin    = htmlspecialchars(commonUtil::stripSlashes($_POST['syslogin']));            
            }
        }   
        return TRUE;
    } 
}

?>
