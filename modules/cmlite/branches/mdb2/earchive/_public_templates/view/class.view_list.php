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
 * view_list class of the template "group_list.tpl.php"
 *
 */
 
class view_list
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
    function view_list()
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
     * Execute the view of the template "group_list.tpl.php"
     *
     * @return bool true on success else false
     * @todo validate $_GET['lid']
     */
    function perform()
    {
        /* check if registered user is required to access this list */
        $this->B->M( MOD_EARCHIVE, 
                     'have_access', 
                     array( 'lid' => (int)$_GET['lid'])); 
        
        /* get all available email lists and store the result in the array $B->tpl_list */
        $this->B->M( MOD_EARCHIVE, 
                     'get_lists', 
                     array( 'var'    => 'tpl_list', 
                            'fields' => array('lid','name','email','description','status'))); 
        
        /* get the messages of the requested email list and store the result in the array $B->tpl_msg 
           assign template vars with message data */
        $this->B->M( MOD_EARCHIVE, 
                     'get_messages', 
                     array( 'lid'    => (int)$_GET['lid'], 
                            'var'    => 'tpl_msg',
                            'fields' => array('mid','lid','subject','sender','mdate'),
                            'order'  => 'mdate DESC',
                            'pager'  => array('var' => 'tpl_prevnext', 'limit' => 15, 'delta' => 3)));

        return TRUE;
    }    
}

?>
