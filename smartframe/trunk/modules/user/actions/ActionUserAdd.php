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
 * ActionUserAdd class 
 * 
 * USAGE:
 * 
 * $model->action('user','add',
 *                array('error' => & array(),
 *                      'user'  => array('login'   => string,
 *                                       'passwd'  => string,
 *                                       'status'  => int,
 *                                       'role'    => int,
 *                                       'name'    => string,
 *                                       'lastname => string,
 *                                       'email'   => string
 *                                       )))
 *
 */

include_once(SMART_BASE_DIR . 'modules/user/includes/ActionUser.php');
 
class ActionUserAdd extends ActionUser
{
    /**
     * add user
     *
     * @param array $data
     * @return int user id or false on error
     */
    function perform( $data = FALSE )
    { 
        // encrypt password
        $data['user']['passwd'] = md5($data['user']['passwd']);
        
        $comma = '';
        $fields = '';
        $quest = '';
        
        foreach($data['user'] as $key => $val)
        {
            $fields .= $comma.'`'.$key.'`';
            $quest .= $comma.'?';
            $comma = ',';
        }
        
        $sql = "INSERT INTO {$this->config['dbTablePrefix']}user_user
                   ($fields)
                  VALUES
                   ($quest)";

        $stmt = $this->model->dba->prepare($sql);                    
        
        foreach($data['user'] as $key => $val)
        {
            // set field type
            $methode = 'set'.$this->tblFields_user[$key];
            // add value
            $stmt->$methode($val);
        }
        $stmt->execute();
        // return user id
        return $this->model->dba->lastInsertID();
    }
    
    /**
     * validate user data
     *
     * @param array $data User data
     * @return bool 
     */    
    function validate( $data = FALSE )
    {
        // check if database fields exists
        foreach($data['user'] as $key => $val)
        {
            if(!isset($this->tblFields_user[$key]))
            {
                throw new SmartModelException("Field '".$key."' dosent exists!");
            }
        }

        if(!isset($data['error']))
        {
            throw new SmartModelException("'error' var isnt set!");
        }
        elseif(!is_array($data['error']))
        {
            throw new SmartModelException("'error' var isnt from type array!");
        }
        
        // reset error array
        $data['error'] = array();
        
        // Check user data field values
        //
        if(!is_string($data['user']['login']))
        {
            throw new SmartModelException("'login' isnt from type string!");
        }
        elseif(empty($data['user']['login']))
        {
            $data['error'][] = 'Login is empty';      
        }
        
        if(!is_string($data['user']['passwd']))
        {
            throw new SmartModelException("'passwd' isnt from type string!");
        }
        elseif(empty($data['user']['passwd']))
        {
            $data['error'][] = 'Password is empty';       
        }   

        $str_len = strlen( $data['user']['login'] );
        if( ($str_len < 3) || ($str_len > 20) )
        {
            $data['error'][] = 'Only 3-20 login chars are accepted.';     
        }

        $str_len = strlen( $data['user']['passwd'] );
        if( ($str_len < 3) || ($str_len > 20) )
        {
            $data['error'][] = 'Only 3-20 password chars are accepted.';     
        }
        
        if( @preg_match("/[^a-zA-Z0-9_-]/", $data['user']['login']) )
        {
            $data['error'][] = 'Login entry is not correct! Only 3-30 chars a-zA-Z0-9_- are accepted.';       
        }  
        
        if( @preg_match("/[^a-zA-Z0-9_-]/", $data['user']['passwd']) )
        {
            $data['error'][] = 'Password entry is not correct! Only 3-30 chars a-zA-Z0-9_- are accepted.';       
        }        

        if(!is_string($data['user']['name']))
        {
            throw new SmartModelException("'name' isnt from type string!");
        }    
        elseif(empty($data['user']['name']))
        {
            $data['error'][] = 'Name is empty';       
        }

        $str_len = strlen( $data['user']['name'] );
        if( $str_len > 30 )
        {
            $data['error'][] = 'Max 30 Name chars are accepted.';       
        }

        if(!is_string($data['user']['lastname']))
        {
            throw new SmartModelException("'lastname' isnt from type string!");
        }            
        elseif(empty($data['user']['lastname']))
        {
            $data['error'][] = 'Lastname is empty';       
        } 

        $str_len = strlen( $data['user']['lastname'] );
        if( $str_len > 30 )
        {
            $data['error'][] = 'Max 30 lastname chars are accepted.';      
        }

        if(!is_string($data['user']['email']))
        {
            throw new SmartModelException("'email' isnt from type string!");
        }    
        elseif( empty($data['user']['email']) )
        {
            $data['error'][] = 'Email entry is empty!';      
        } 

        $str_len = strlen( $data['user']['email'] );
        if( $str_len > 500 )
        {
            $data['error'][] = 'Max 500 email chars are accepted.';       
        }

        if( !@preg_match("/^[a-zA-Z0-9_.+-]+@[^@]+[^@.]\.[a-zA-Z]{2,}$/", $data['user']['email']) )
        {
            $data['error'][] = 'Email format is not correct!';      
        } 

        if(!is_int($data['user']['status']))
        {
            throw new SmartModelException("'status' isnt from type int!");
        }            
        elseif( ($data['user']['status'] <= 0) || ($data['user']['status'] > 2) )
        {
            $data['error'][] = 'Status value must be 1 or 2';      
        }         
        
        if(!is_int($data['user']['role']))
        {
            throw new SmartModelException("'role' isnt from type int!");
        }    
        elseif( ($data['user']['role'] < 0) || ($data['user']['role'] > 250) )
        {
            $data['error'][] = 'Role value must be between 0 and 250';      
        }                 
    
        // Check if login exists
        if($this->loginExists($data['user']['login']) > 0)
        {
            $data['error'][] = 'Login exists';
        }    
        
        if( count($data['error']) > 0 )
        {
            return FALSE;
        }
        
        return TRUE;
    }
    
    /**
     * check if login exist
     *
     * @param string $login User login
     * @return int Number of logins
     */    
    function loginExists( $login )
    {
        $sql = "
            SELECT
                id_user
            FROM
                {$this->config['dbTablePrefix']}user_user
            WHERE
                login='$login'";
        
        $stmt = $this->model->dba->query($sql);

        return $stmt->numRows();    
    } 
    
}

?>
