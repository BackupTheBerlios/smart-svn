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
 
class view_search extends view
{
     /**
     * Default template for this view
     * @var string $template
     */
    var $template = 'search';
    
    /**
     * Execute the view of the template "group_search.tpl.php"
     *
     * @return mixed (object) this object on success else (bool) false on error
     */
    function & perform()
    { 
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


        return $this;
    }    
}

?>
