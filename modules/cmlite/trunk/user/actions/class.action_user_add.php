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
 * action_user_add class 
 *
 */
 
class action_user_add extends action
{
    /**
     * add user
     *
     * @param array $data
     * @return int user id or false on error
     */
    function perform( & $data )
    { 
        $set = '';
        $comma = '';
        $fields = '';
        $values = '';
        
        foreach($data['user_data'] as $key => $val)
        {
            $fields .= $comma.$key;
            $values .= $comma.$this->B->db->quoteSmart( $val );
            $comma = ',';
        }
        
        $sql = '
            INSERT INTO 
                '.$this->B->sys['db']['table_prefix'].'user_users
                ('.$fields.')
            VALUES
                ('.$values.')';
         
        $res = $this->B->db->query($sql);

        if (DB::isError($res)) 
        {
            trigger_error($res->getMessage()."\n".$res->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            $this->B->$data['error'] = 'Unexpected error';
            return FALSE;
        }

        $sql = 'SELECT LAST_INSERT_ID() AS uid';
        
        $result = $this->B->db->getRow($sql, array(), DB_FETCHMODE_ASSOC);
       
        return $result['uid'];
    }
    
    /**
     * validate user data and check permission to add user
     *
     * @param array $data User data
     * @return bool 
     */    
    function validate( & $data )
    {
        // check permission to add new user
        M( MOD_USER,
           'permission',
           array( 'action' => 'add'));

        // check if all requested fields exists
        if (FALSE == $this->_accepted_fields ( $data ) )
        {
            return SF_NO_VALID_ACTION;
        }
        
        // Check user data field values
        //
        if(empty($data['user_data']['login']))
        {
            $this->B->$data['error'] = 'Login is empty';
            return SF_NO_VALID_ACTION;        
        }
        
        if(empty($data['user_data']['passwd']))
        {
            $this->B->$data['error'] = 'Password is empty';
            return SF_NO_VALID_ACTION;        
        }   

        $str_len = strlen( $data['user_data']['login'] );
        if( ($str_len < 3) || ($str_len > 20) )
        {
            $this->B->$data['error'] = 'Only 3-20 login chars are accepted.';
            return SF_NO_VALID_ACTION;        
        }

        $str_len = strlen( $data['user_data']['passwd'] );
        if( ($str_len < 3) || ($str_len > 20) )
        {
            $this->B->$data['error'] = 'Only 3-20 password chars are accepted.';
            return SF_NO_VALID_ACTION;        
        }
        
        if( @preg_match("/[^a-zA-Z0-9_-]/", $data['user_data']['login']) )
        {
            $this->B->$data['error'] = 'Login entry is not correct! Only 3-30 chars a-zA-Z0-9_- are accepted.';
            return SF_NO_VALID_ACTION;        
        }  
        
        if( @preg_match("/[^a-zA-Z0-9_-]/", $data['user_data']['passwd']) )
        {
            $this->B->$data['error'] = 'Login entry is not correct! Only 3-30 chars a-zA-Z0-9_- are accepted.';
            return SF_NO_VALID_ACTION;        
        }        
    
        if(empty($data['user_data']['forename']))
        {
            $this->B->$data['error'] = 'Forename is empty';
            return SF_NO_VALID_ACTION;        
        }

        $str_len = strlen( $data['user_data']['forename'] );
        if( $str_len > 30 )
        {
            $this->B->$data['error'] = 'Max 30 forename chars are accepted.';
            return SF_NO_VALID_ACTION;        
        }
        
        if(empty($data['user_data']['lastname']))
        {
            $this->B->$data['error'] = 'Lastname is empty';
            return SF_NO_VALID_ACTION;        
        } 

        $str_len = strlen( $data['user_data']['lastname'] );
        if( $str_len > 30 )
        {
            $this->B->$data['error'] = 'Max 30 lastname chars are accepted.';
            return SF_NO_VALID_ACTION;        
        }

        if( empty($data['user_data']['email']) )
        {
            $this->B->$data['error'] = 'Email entry is empty!';
            return SF_NO_VALID_ACTION;        
        } 

        $str_len = strlen( $data['user_data']['email'] );
        if( $str_len > 500 )
        {
            $this->B->$data['error'] = 'Max 500 email chars are accepted.';
            return SF_NO_VALID_ACTION;        
        }

        if( !@preg_match("/^[a-zA-Z0-9_.+-]+@[^@]+[^@.]\.[a-zA-Z]{2,}$/", $data['user_data']['email']) )
        {
            $this->B->$data['error'] = 'Email entry is not correct!';
            return SF_NO_VALID_ACTION;        
        } 
        
        if(!is_int($data['user_data']['status']))
        {
            $this->B->$data['error'] = 'Status value must an int';
            return SF_NO_VALID_ACTION;        
        } 
        
        if( ($data['user_data']['status'] < 0) || ($data['user_data']['status'] > 2) )
        {
            $this->B->$data['error'] = 'Status value must be between 0 an 2';
            return SF_NO_VALID_ACTION;        
        }         
        
        if(!is_int($data['user_data']['rights']))
        {
            $this->B->$data['error'] = 'Rights value must an int';
            return SF_NO_VALID_ACTION;        
        }        

        if( ($data['user_data']['rights'] < 1) || ($data['user_data']['rights'] > 5) )
        {
            $this->B->$data['error'] = 'Rights value must be between 1 an 5';
            return SF_NO_VALID_ACTION;        
        } 
    
        // Check if login exists
        if($this->_login_exists($data['user_data']['login']) == 1)
        {
            $this->B->$data['error'] = 'Login exists';
            return SF_NO_VALID_ACTION;
        }    
        
        return SF_IS_VALID_ACTION;
    }
    
    /**
     * check if login exist
     *
     * @param string $login User login
     * @return int Number of logins
     */    
    function _login_exists( $login )
    {
        $login = $this->B->db->quoteSmart( $login );
        
        $sql = '
            SELECT
                uid
            FROM
                '.$this->B->sys['db']['table_prefix'].'user_users
            WHERE
                login='.$login;
        
        $result = $this->B->db->query($sql);
        
        return $result->numRows();    
    } 
    
    /**
     * check if all requested fields exists 
     *
     * @param array $data User data
     * @return bool 
     */     
    function _accepted_fields ( & $data )
    {
        $accepted_fields = array( 'login'    => TRUE,
                                  'passwd'   => TRUE,
                                  'forename' => TRUE,
                                  'lastname' => TRUE,
                                  'email'    => TRUE,
                                  'status'   => TRUE,
                                  'rights'   => TRUE);

        foreach( $data['user_data'] as $key => $val )
        {
            if(!isset($accepted_fields[$key]))
            {
                $this->B->$data['error'] = 'This field dosent exists: ' . $key;
                return FALSE;                
            }
        }  
        
        return TRUE;
    }    
}

?>
