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
 * user_view_default class of the template "default.tpl.php"
 *
 */
 
class user_view_default
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
    function user_view_default()
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
     * create the template variables
     *
     * @return bool true
     * @todo pagination results
     */
    function perform()
    {
        // get all users
        $this->B->M( MOD_USER,
                     'get_users',
                     array( 'error'  => 'tpl_error',
                            'result' => 'all_users',
                            'fields' => array('uid',
                                              'rights',
                                              'status',
                                              'email',
                                              'login',
                                              'forename',
                                              'lastname')));
        return TRUE;
    }    
}

?>
