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
 * view_navigation class of the template "tpl.navigation.php"
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
     * Execute the view of the template "tpl.index.php"
     *
     * @return bool true on success else false
     */
    function perform()
    {
        //get all available email lists and store the result in the array $B->tpl_list
        M( MOD_EARCHIVE, 
           'get_lists', 
           array( 'var'    => 'tpl_list', 
                  'fields' => array('lid','name','email','description','status'))); 

        return TRUE;
    }    
}

?>
