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
 * action_user_update class 
 *
 */
 
class action_user_update extends action
{
    /**
     * update user data
     *
     * @param array $data
     * @return bool true or false on error
     */
    function perform( $data )
    {
        $set = '';
        $comma = '';
        $fields = '';
        $values = '';
        
        foreach($data['user_data'] as $key => $val)
        {
            $set .= $comma.$key.'='.$this->B->db->quoteSmart( $val );
            $comma = ',';
        }
        
        $sql = '
            UPDATE 
                '.$this->B->sys['db']['table_prefix'].'user_users
            SET
                '.$set.'
            WHERE
                uid='.(int)$data['user_id'];
        
        $result = $this->B->db->query($sql);
        
        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        } 
        
        return TRUE;
    } 
    /**
     * validate user data 
     *
     * @param array $data User data
     * @return bool 
     */    
    function validate( & $data )
    { 
        // check if all requested fields exists
        if (FALSE == $this->_accepted_fields ( $data ) )
        {
            return SF_NO_VALID_ACTION;
        }

        if(!is_int($data['user_id']))
        {
            $this->B->$data['error'] = 'user_id must be an int value';
            return SF_NO_VALID_ACTION;        
        }
                                  
        // Check user data field values
        //
        $str_len = strlen( $data['user_data']['passwd'] );
        if( isset($data['user_data']['passwd']) && (($str_len < 3) || ($str_len > 20)) )
        {
            $this->B->$data['error'] = 'Only 3-20 password chars are accepted.';
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
        
        return SF_IS_VALID_ACTION;
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
