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
 * ActionArticleLogout class 
 *
 * USAGE:
 *
 * $model->action('article','logout')
 * 
 */
 
class ActionArticleLogout extends SmartAction
{
    /**
     * process logout 
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    { 
        // free locks from this user
        $this->model->action('article','lock',
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
