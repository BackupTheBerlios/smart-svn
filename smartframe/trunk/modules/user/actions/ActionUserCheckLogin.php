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
 * ActionUserCheckLogin class 
 *
 */
 
class ActionUserCheckLogin extends SmartAction
{
    /**
     * Check login data and set session vars and url forward  on success
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {    
        $sql = "SELECT 
                    id_user,
                    role
                FROM
                    {$this->config['dbTablePrefix']}user_user
                WHERE
                    login=?
                AND
                    passwd=?
                AND
                    status=2";
        
        $stmt = $this->model->db->prepareStatement($sql, ResultSet::FETCHMODE_ASSOC);

        $stmt->setString(1, $data['login']);
        $stmt->setString(2, md5($data['passwd']));
        
        $result = $stmt->executeQuery();
        
        $result->first();
        
        $field = $result->getRow();
        
        if(is_array($field) && (count($field) > 0))
        {
            $this->model->session->set('loggedUserId',   $field['id_user']);
            $this->model->session->set('loggedUserRole', $field['role']);  
            
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
        if( @preg_match("/[^a-zA-Z0-9]/", $data['login']) )
        {
            $this->B->$data['error'] = 'Login entry is not correct! Only 3-30 chars a-zA-Z0-9 are accepted.';
            return FALSE;        
        }
        if( @preg_match("/[^a-zA-Z0-9]/", $data['passwd']) )
        {
            $this->B->$data['error'] = 'Password entry is not correct! Only 3-30 chars a-zA-Z0-9 are accepted.';
            return FALSE;        
        }  
        
        return TRUE;
    }
}

?>
