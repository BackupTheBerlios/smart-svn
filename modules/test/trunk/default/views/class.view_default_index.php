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
 * view_default_index class
 *
 */

class view_default_index extends view
{
     /**
     * Default template for this view
     * @var string $template
     */
    var $template = 'default_index';
    
     /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    var $template_folder = 'modules/default/templates/';
    
    /**
     * Execute the view of the template "tpl.default_index.php"
     *
     * @return bool true on success else false
     */
    function perform()
    {
        return TRUE;
    }    
}

?>
