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
 * view_logout view class
 *
 */
 
class view_logout extends view
{
    /**
     * Template render flag
     * @var bool $render_template
     */    
    var $render_template = SF_TEMPLATE_RENDER_NONE; // We need no template here

    /**
     * Perform on the logout view. We dont need any template to render.
     *
     * @return bool true 
     */
    function perform()
    {
        // each module can do clean up before logout
        // see modules/xxx/actions/class.xxx_sys_logout.php
        B('sys_logout');

        // reload the main page
        header ( 'Location: '.SF_BASE_LOCATION.'/'.SF_CONTROLLER );
        exit;

        return TRUE;
    }    
}

?>