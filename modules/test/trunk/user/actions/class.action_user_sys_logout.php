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
 * action_user_sys_logout class 
 *
 */
 
class action_user_sys_logout extends action
{
    /**
     * If a logout request was done
     *
     * @param array $data
     */
    function perform( $data )
    {            
        // include here additional clean up code.  
        // destroying sessions,....
        $this->B->session->destroy();        
    }    
}

?>