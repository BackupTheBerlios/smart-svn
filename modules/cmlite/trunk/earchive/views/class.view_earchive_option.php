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
 * view_earchive_option class
 *
 */

class view_earchive_option extends view
{
     /**
     * Default template for this view
     * @var string $template
     */
    var $template = 'earchive_option';
    
     /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    var $template_folder = 'modules/earchive/templates/';
    
    /**
     * Execute the view of the template "tpl.earchive_option.php"
     *
     * @return bool true on success else false
     */
    function perform()
    {

        return TRUE;
    }    
}

?>
