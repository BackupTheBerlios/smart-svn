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
 
class view_list extends view
{
     /**
     * Default template for this view
     * @var string $template
     */
    var $template = 'list';
    
    /**
     * Execute the view of the template "group_list.tpl.php"
     *
     * @return mixed (object) this object on success else (bool) false on error
     * @todo validate $_GET['lid']
     */
    function perform()
    {
        /* check if registered user is required to access this list */
        $this->B->M( MOD_EARCHIVE, 
                     'have_access', 
                     array( 'lid' => (int)$_GET['lid'])); 

        // Prepare variables for the html view -> flat/tree
        $this->B->tpl_select_tree = '';
        $this->B->tpl_select_flat = '';
        if($_REQUEST['mode'] == 'tree')
        {
            $this->B->tpl_mode = 'tree';
            $this->B->tpl_select_tree = 'selected="selected"';
        }
        else
        {
            $this->B->tpl_mode = 'flat'; 
            $this->B->tpl_select_flat = 'selected="selected"';
        }
        
        /* get the messages of the requested email list and store the result in the array $B->tpl_msg 
           assign template vars with message data */
        $this->B->M( MOD_EARCHIVE, 
                     'get_messages', 
                     array( 'lid'    => (int)$_GET['lid'], 
                            'var'    => 'tpl_msg',
                            'mode'   => $this->B->tpl_mode,
                            'fields' => array('mid','lid','subject','sender','mdate'),
                            'order'  => 'mdate DESC',
                            'pager'  => array( 'var'   => 'tpl_prevnext', 
                                               'limit' => 15, 
                                               'delta' => 3)));
 
        
        return $this;
    }    
}

?>
