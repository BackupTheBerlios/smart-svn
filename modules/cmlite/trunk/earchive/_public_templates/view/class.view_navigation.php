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
 * view_navigation class of the template "group_index.tpl.php"
 *
 */
 
class view_navigation extends view
{
     /**
     * Default template
     * @var string $template
     */
    var $template = 'navigation';

    /**
     * Execute the view of the template "group_index.tpl.php"
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

        return $this;
    } 
    
    /**
     * disable prepend filter chain by overloading the methode of the parent class
     *
     */
    function prependFilterChain()
    { 
    }        
}

?>
