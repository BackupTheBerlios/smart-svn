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
 * ActionUserCheckLogin class 
 *
 * USAGE:
 *
 * $is_login_ok = $model->action('user','checkLogin',
 *                               array('login'  => string,   // [a-zA-Z0-9_-] <= 50 chars
 *                                     'passwd' => string))  // [a-zA-Z0-9_-] <= 50 chars
 *
 * return TRUE or FALSE
 * if TRUE this action sets 2 session variables:
 * - loggedUserId
 * - loggedUserRole
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
                    login='{$login}'
                AND
                    passwd='{$pass}'
                AND
                    status=2";

        $rs = $this->model->dba->query($sql);

        if($row = $rs->fetchAssoc())
        {
            $this->model->session->set('loggedUserId',   $row['id_user']);
            $this->model->session->set('loggedUserRole', $row['role']);

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
        if(!is_string($data['login']))
        {
            throw new SmartModelException("'login' isnt from type string!");
        }
        elseif( preg_match("/[^a-zA-Z0-9_-]/", $data['login']) )
        { 
            return FALSE;        
        }
        elseif(strlen($data['login']) > 50)
        {
            return FALSE;
        }

        if(!is_string($data['passwd']))
        {
            throw new SmartModelException("'passwd' isnt from type string!");
        }        
        elseif( preg_match("/[^a-zA-Z0-9_-]/", $data['passwd']) )
        {
            return FALSE;        
        }  
        elseif(strlen($data['passwd']) > 50)
        {
            return FALSE;
        }
        
        return TRUE;
    }
}

?>
