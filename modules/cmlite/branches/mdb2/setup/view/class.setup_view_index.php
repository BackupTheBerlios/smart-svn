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
            $_data = array('dbhost'         => (string)$_POST['dbhost'],
                           'dbuser'         => (string)$_POST['dbuser'],
                           'dbpasswd'       => (string)$_POST['dbpasswd'],
                           'dbname'         => (string)$_POST['dbname'],
                           'dbtype'         => (string)$_POST['dbtype'],
                           'dbtablesprefix' => (string)$_POST['dbtablesprefix'],
                           'dbcreate'       => (string)$_POST['create_db'],
                           'charset'        => (string)$_POST['charset'],
                           'userlogin'      => (string)$_POST['userlogin'],
                           'username'       => (string)$_POST['username'],
                           'userlastname'   => (string)$_POST['userlastname'],
                           'userpasswd1'    => (string)$_POST['userpasswd1'],
                           'userpasswd2'    => (string)$_POST['userpasswd2']);
                           
            if( FALSE == $this->B->M(MOD_SETUP, 'sys_setup', $_data) )
            {
                $this->B->form_host        = htmlspecialchars(commonUtil::stripSlashes($_POST['dbhost']));
                $this->B->form_user        = htmlspecialchars(commonUtil::stripSlashes($_POST['dbuser']));
                $this->B->form_dbname      = htmlspecialchars(commonUtil::stripSlashes($_POST['dbname']));
                $this->B->form_tableprefix = htmlspecialchars(commonUtil::stripSlashes($_POST['dbtablesprefix']));
                $this->B->form_sysname     = htmlspecialchars(commonUtil::stripSlashes($_POST['username']));
                $this->B->form_syslastname = htmlspecialchars(commonUtil::stripSlashes($_POST['userlastname']));
                $this->B->form_syslogin    = htmlspecialchars(commonUtil::stripSlashes($_POST['userlogin']));            
            }
        }   
        return TRUE;
    } 
}

?>
