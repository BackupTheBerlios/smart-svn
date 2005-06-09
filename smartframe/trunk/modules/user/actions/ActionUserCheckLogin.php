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
     * Check login
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {
        $login = $this->model->dba->escape($data['login']);
        $pass =  md5($data['passwd']);  
        
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

        $stmt = $this->model->dba->prepare($sql);

        $stmt->setString( $data['login'] );
        $stmt->setString( md5($data['passwd'] ));       
        
        $id_user = 0;
        $role    = 0;
        $stmt->bindResult(array( & $id_user, & $role ));
        
        $stmt->execute();

        if($stmt->fetch())
        {
            $this->model->session->set('loggedUserId',   $id_user);
            $this->model->session->set('loggedUserRole', $role);

            return TRUE;
        }
        
        return FALSE;
    } 

    /**
     * Validate data before passed to the perform methode
     *
     * @param array $data
     */    
    public function validate( $data = FALSE )
    {
        if( @preg_match("/[^a-zA-Z0-9]/", $data['login']) )
        { 
            return FALSE;        
        }
        if( @preg_match("/[^a-zA-Z0-9]/", $data['passwd']) )
        {
            return FALSE;        
        }  
        
        return TRUE;
    }
}

?>
