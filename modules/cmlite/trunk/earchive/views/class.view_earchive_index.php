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
 * earchive_view_index class
 *
 */

class view_earchive_index extends view
{
     /**
     * Default template for this view
     * @var string $template
     */
    var $template = 'earchive_index';
    
     /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    var $template_folder = 'modules/earchive/templates/';
    
    /**
     * Execute the view of the template "tpl.earchive_index.php"
     *
     * @return bool true on success else false
     */
    function perform()
    {
        // check permission to access this module
        if( FALSE == F( MOD_EARCHIVE, 'permission', array('action' => 'access')))
        {
            @header('Location: '.SF_BASE_LOCATION.'/'.SF_CONTROLLER.'?admin=1');
            exit;      
        }
    
        return TRUE;
    }    
}

?>
