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
 * view_earchive_default class of the template "tpl.earchive_default.php"
 *
 */
 
class view_earchive_default extends view
{
     /**
     * Default template for this view
     * @var string $template
     */
    var $template = 'earchive_default';

     /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    var $template_folder = 'modules/earchive/templates/';
    
    /**
     * Execute the view of the template "default.tpl.php"
     *
     * @return bool true on success else false
     */
    function perform()
    {
        // get all available lists
        M( MOD_EARCHIVE, 
           'get_lists', 
           array( 'var'    => 'all_lists', 
                  'fields' => array('lid','status','email','name','description')));        
        
        return TRUE;
    }      
}

?>
