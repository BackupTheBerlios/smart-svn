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
 * user_sys_authenticate class 
 *
 */
 
class user_sys_authenticate
{
    /**
     * Global system instance
     * @var object $B
     */
    var $B;
    
    /**
     * constructor
     *
     */
    function user_sys_authenticate()
    {
        $this->__construct();
    }

    /**
     * constructor php5
     *
     */
    function __construct()
    {
        $this->B = & $GLOBALS['B'];
    }
    
    /**
     * Perform on admin requests for this module
     *
     * @param array $data
     */
    function perform( $data )
    {    
        include_once(SF_BASE_DIR.'modules/user/includes/class.auth.php');
        
        $this->B->auth = & new auth( SF_SECTION );  
        
        $this->B->is_logged = $this->B->auth->isLogged();
        
        if( (SF_SECTION == 'admin') && (FALSE == $this->B->is_logged) )
        {
            $_REQUEST['view'] = 'login';
            $_REQUEST['m']    = SF_AUTH_MODULE;
            
            $this->B->tpl_isLogged = FALSE;
        }
        else
        {
            $this->B->tpl_isLogged = $this->B->is_logged;
        }
        return TRUE;
    } 
}

?>
