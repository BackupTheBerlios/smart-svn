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
 * The earchive rights class 
 *
 * Existing rights levels:
 * 1 Registered (No access to the admin interface. Only access to protected content of the public page)
 * 2 Contributor (Can propose content. But not modify)
 * 3 Author (Can add content and modify own content)
 * 4 Editor (Can add content, modify content and add/mod/delete users below own rights level)
 * 5 Administrator (Can do every thing)
 *
 * For this module only 4 + 5 are considered
 *
 */
 
class earchive_rights
{ 
    
    /**
     * check rights to add/modify a list
     * 4 or 5 (editor or administrator) required
     *
     * @return bool
     */
    function ask_access_to_list ()
    {       
        if( ($GLOBALS['B']->auth->user_rights > 3) )
        {
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * check rights to delete a list
     * 5 (administrator required)
     *
     * @return bool
     */
    function ask_access_to_delete_list ()
    {       
        if( ($GLOBALS['B']->auth->user_rights == 5) )
        {
            return TRUE;
        }
        return FALSE;
    }
}

?>
