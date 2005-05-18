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
        
        $stmt = $this->model->db->prepareStatement($sql, ResultSet::FETCHMODE_ASSOC);

        $stmt->setInt(1, $data['id_user']);
        
        $result = $stmt->executeQuery();
        
        $result->first();
        
        $field = $result->getRow();
        
        if(!is_array($field) || !isset($field['role']))
        {
            return FALSE;
        }
        
        // accord permission if the role of the user to edit
        // is greater than the role of the logged user
        if($field['role'] > $this->model->session->get('loggedUserRole'))
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
        if( @preg_match("/[^0-9]/", $data['id_user']) )
        {
            return FALSE;        
        }
        return TRUE;
    }
}

?>
