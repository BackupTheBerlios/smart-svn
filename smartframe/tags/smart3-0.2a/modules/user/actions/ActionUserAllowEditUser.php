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
 * ActionUserAllowEditUser class 
 *
  * USAGE:
  * 
  * $model->action('user','allowEditUser',
  *                array('id_user'  => int))
  *
 */
 
class ActionUserAllowEditUser extends SmartAction
{
    /**
     * Check if the logged user have rights to edit data 
     * of a requested user (id_user)
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {   
        // allow the logged user edit its own data
        if($data['id_user'] == $this->model->session->get('loggedUserId'))
        {
            return TRUE;
        }
        
        $sql = "SELECT 
                    role
                FROM
                    {$this->config['dbTablePrefix']}user_user
                WHERE
                    id_user=?";
        
        $stmt = $this->model->dba->prepare($sql);
        $stmt->setInt($data['id_user']);
        
        $role = FALSE;
        $stmt->bindResult( array(&$role) ); 
        
        $stmt->execute();
        $stmt->fetch();
        
        if($role == FALSE)
        {
            return FALSE;
        }
        
        // accord permission if the role of the user to edit
        // is greater than the role of the logged user
        if($role > $this->model->session->get('loggedUserRole'))
        {
            return TRUE;
        }

        return FALSE;
    } 

    /**
     * Validate data before passed to the perform methode
     *
     * @param array $data
     */    
    function validate( $data = FALSE )
    {
        if( !isset($data['id_user']) || !is_int($data['id_user']) )
        {
            return FALSE;        
        }
        return TRUE;
    }
}

?>
