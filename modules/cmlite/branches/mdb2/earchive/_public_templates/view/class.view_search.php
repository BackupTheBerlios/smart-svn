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
 * view_search class of the template "group_search.tpl.php"
 *
 */
 
class view_search
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
    function view_search()
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
     * Execute the view of the template "group_search.tpl.php"
     *
     * @return bool true on success else false
     */
    function perform()
    {
        //get all available email lists and store the result in the array $B->tpl_list
        $this->B->M( MOD_EARCHIVE, 
                     'get_lists', 
                     array( 'var'    => 'tpl_list', 
                            'fields' => array('lid','name','email','description','status'))); 

        //get the messages of the searching result and store the result in the array $B->tpl_msg
        $this->B->M( MOD_EARCHIVE, 
                     'search', 
                     array( 'var'      => 'tpl_msg', 
                            'search'   => $_REQUEST['search'], 
                            'bool'     => 'and', 
                            'order'    => 'mdate desc', 
                            'limit'    => 100, 
                            'fields'   => array('mid','lid','subject','sender','mdate'),
                            'get_list' => TRUE));                        


        return TRUE;
    }    
}

?>
