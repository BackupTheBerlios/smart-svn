<?php
// ----------------------------------------------------------------------
// Smart3 PHP Framework
// Copyright (c) 2004, 2005
// by Armand Turpel < framework@smart3.org >
// http://www.smart3.org/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * ActionLinkLogout class 
 *
 * USAGE:
 *
 * $model->action('link','logout')
 * 
 */
 
class ActionLinkLogout extends SmartAction
{
    /**
     * process logout 
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    { 
        // free locks from this user
        $this->model->action('link','lock',
                             array('job'     => 'unlock_from_user',
                                   'id_user' => (int)$data['loggedUserId']));
    }
    /**
     * validate the parameters passed in the data array
     *
     * @param array $data
     * @return bool
     */    
    public function validate(  $data = FALSE  )
    {
        if( !isset($data['loggedUserId']) || !is_int($data['loggedUserId']) )
        {        
            trigger_error('"loggedUserId" must be defined and from type int', E_USER_ERROR); 
            return FALSE;        
        }
    
        return TRUE;
    }    
}

?>
