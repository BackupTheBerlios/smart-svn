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
 * common_view_index class of the template "index.tpl.php"
 *
 */

class view_entry_index extends view
{
     /**
     * Default template for this view
     * @var string $template
     */
    var $template = 'entry_index';
    var $template_folder = 'modules/entry/templates/';

    
    /**
     * Execute the view of the template "index.tpl.php"
     *
     * @return bool true on success else false
     */
    function perform()
    {
        return TRUE;
    }      
}

?>
