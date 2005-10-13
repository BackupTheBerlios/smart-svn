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
 * ActionUserLogout class 
 *
 * USAGE:
 *
 * $model->action('user','logout')
 * 
 */
 
class ActionUserLogout extends SmartAction
{
    /**
     * process logout 
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    { 
        // free locks from this user
        $this->model->action('user','lock',
                             array('job'     => 'unlock_from_user',
                                   'id_user' => (int)$data['loggedUserId']));
        
        $this->model->session->destroy();
        ob_clean();
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
            throw new SmartModelException ('"loggedUserId" must be defined and from type int'); 
        }
    
        return TRUE;
    }    
}

?>
