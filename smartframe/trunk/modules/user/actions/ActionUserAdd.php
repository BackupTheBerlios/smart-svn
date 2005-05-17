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
        $set = '';
        $comma = '';
        $fields = '';
        $values = '';
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

        $stmt = $this->model->db->prepareStatement($sql);                    
        
        $x = 1;
        foreach($data['user'] as $key => $val)
        {
            $methode = 'set'.$this->tblFields_user[$key];
            $stmt->$methode($x, $val);
            $x++;
        }
       
        $stmt->executeUpdate();
       
        return TRUE;
    }
    
    /**
     * validate user data and check permission to add user
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
        
        // Check user data field values
        //
        if(empty($data['user']['login']))
        {
            $data['error'] = 'Login is empty';
            return FALSE;        
        }
        
        if(empty($data['user']['passwd']))
        {
            $data['error'] = 'Password is empty';
            return FALSE;         
        }   

        $str_len = strlen( $data['user']['login'] );
        if( ($str_len < 3) || ($str_len > 20) )
        {
            $data['error'] = 'Only 3-20 login chars are accepted.';
            return FALSE;       
        }

        $str_len = strlen( $data['user']['passwd'] );
        if( ($str_len < 3) || ($str_len > 20) )
        {
            $data['error'] = 'Only 3-20 password chars are accepted.';
            return FALSE;       
        }
        
        if( @preg_match("/[^a-zA-Z0-9_-]/", $data['user']['login']) )
        {
            $data['error'] = 'Login entry is not correct! Only 3-30 chars a-zA-Z0-9_- are accepted.';
            return FALSE;         
        }  
        
        if( @preg_match("/[^a-zA-Z0-9_-]/", $data['user']['passwd']) )
        {
            $data['error'] = 'Password entry is not correct! Only 3-30 chars a-zA-Z0-9_- are accepted.';
            return FALSE;         
        }        
    
        if(empty($data['user']['name']))
        {
            $data['error'] = 'Name is empty';
            return FALSE;         
        }

        $str_len = strlen( $data['user']['name'] );
        if( $str_len > 30 )
        {
            $data['error'] = 'Max 30 Name chars are accepted.';
            return FALSE;         
        }
        
        if(empty($data['user']['lastname']))
        {
            $data['error'] = 'Lastname is empty';
            return FALSE;         
        } 

        $str_len = strlen( $data['user']['lastname'] );
        if( $str_len > 30 )
        {
            $data['error'] = 'Max 30 lastname chars are accepted.';
            return FALSE;        
        }

        if( empty($data['user']['email']) )
        {
            $data['error'] = 'Email entry is empty!';
            return FALSE;        
        } 

        $str_len = strlen( $data['user']['email'] );
        if( $str_len > 500 )
        {
            $data['error'] = 'Max 500 email chars are accepted.';
            return FALSE;         
        }

        if( !@preg_match("/^[a-zA-Z0-9_.+-]+@[^@]+[^@.]\.[a-zA-Z]{2,}$/", $data['user']['email']) )
        {
            $data['error'] = 'Email entry is not correct!';
            return FALSE;        
        } 
        
        if(!is_int($data['user']['status']))
        {
            $data['error'] = 'Status value must an int';
            return FALSE;         
        } 
        
        if( ($data['user']['status'] < 0) || ($data['user']['status'] > 2) )
        {
            $data['error'] = 'Status value must be between 0 an 2';
            return FALSE;        
        }         
        
        if(!preg_match("/[0-9]*/",$data['user']['role']))
        {
            $data['error'] = 'Rights value must an int';
            return FALSE;        
        }        

        if( ($data['user']['role'] < 10) || ($data['user']['role'] > 100) )
        {
            $data['error'] = 'Rights value must be between 10 an 100';
            return FALSE;        
        } 
    
        // Check if login exists
        if($this->loginExists($data['user']['login']) == 1)
        {
            $data['error'] = 'Login exists';
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
        
        $result = $this->model->db->executeQuery($sql, ResultSet::FETCHMODE_ASSOC);

        $result->first();
        
        $field = $result->getRow();

        if(is_array($field) && (count($field) > 0))
        {
            return TRUE;
        }
        
        return FALSE;    
    } 
    
}

?>
