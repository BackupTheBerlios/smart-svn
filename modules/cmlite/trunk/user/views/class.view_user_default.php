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
 * view_user_default class of the template "tpl.user_default.php"
 *
 */
 
class view_user_default extends view
{
   /**
     * Default template for this view
     * @var string $template
     */
    var $template = 'user_default';
    
   /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    var $template_folder = 'modules/user/templates/';
        
   /**
     * Execute the view of the template "default.tpl.php"
     * create the template variables
     *
     * @return bool true
     * @todo pagination results
     */
    function perform()
    {
        // get all users
        M( MOD_USER,
           'get_users',
           array( 'error'  => 'tpl_error',
                  'result' => 'all_users',
                  'fields' => array('uid',
                                    'rights',
                                    'status',
                                    'email',
                                    'login',
                                    'forename',
                                    'lastname')));
        return TRUE;
    }       
}

?>
