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
 * ActionUserUpdate class 
 *
 */

include_once(SMART_BASE_DIR . 'modules/user/includes/ActionUser.php');
 
class ActionUserUpdate extends ActionUser
{
    /**
     * update user
     *
     * @param array $data
     * @return int user id or false on error
     */
    function perform( $data = FALSE )
    { 
        // encrypt password if not empty
        if(isset($data['fields']['passwd']) && !empty($data['fields']['passwd']))
        {
                $data['fields']['passwd'] = md5($data['fields']['passwd']);
        }  
        
        $comma  = "";
        $fields = "";
        
        foreach($data['fields'] as $key => $val)
        {
            $fields .= $comma."`".$key."`='".$this->model->dba->escape($val)."'";
            $comma   = ",";
        }
        
        $sql = "UPDATE {$this->config['dbTablePrefix']}user_user
                  SET
                   $fields
                  WHERE
                   `id_user`={$data['id_user']}";

        $this->model->dba->query($sql);     
        
        return TRUE;
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
        foreach($data['fields'] as $key => $val)
        {
            if(!isset($this->tblFields_user[$key]))
            {
                throw new SmartModelException("Field '".$key."' dosent exists!");
            }
        }

        if(!isset($data['error']))
        {
            throw new SmartModelException("'error' isnt set");
        }
        elseif(!is_array($data['error']))
        {
            throw new SmartModelException("'error' isnt from type array");
        }

        if(isset($data['fields']['passwd']) && !empty($data['fields']['passwd']))
        {
            if(!is_string($data['fields']['passwd']))
            {
                throw new SmartModelException('"passwd" isnt from type string');                     
            }
            
            $str_len = strlen( $data['fields']['passwd'] );
            if( ($str_len < 3) || ($str_len > 20) )
            {
                $data['error'][] = 'Only 3-20 password chars are accepted.';
                return FALSE;       
            }
        
            if( @preg_match("/[^a-zA-Z0-9_-]/", $data['fields']['passwd']) )
            {
                $data['error'][] = 'Password entry is not correct! Only 3-30 chars a-zA-Z0-9_- are accepted.';
                return FALSE;         
            }        
        }
        
        if(isset($data['fields']['name']))
        {
            if(!is_string($data['fields']['name']))
            {
                throw new SmartModelException('"name" isnt from type string');                     
            }
            
            if(empty($data['fields']['name']))
            {
                $data['error'][] = 'Name is empty';
                return FALSE; 
            }
            
            $str_len = strlen( $data['fields']['name'] );
            if( $str_len > 30 )
            {
                $data['error'][] = 'Max 30 Name chars are accepted.';
                return FALSE;         
            }            
        }

        if(isset($data['fields']['lastname']))
        {
            if(!is_string($data['fields']['lastname']))
            {
                throw new SmartModelException('"name" isnt from type string');                     
            }
            
            if(empty($data['fields']['lastname']))
            {
                $data['error'][] = 'Lastname is empty';
                return FALSE;         
            } 

            $str_len = strlen( $data['fields']['lastname'] );
            if( $str_len > 30 )
            {
                $data['error'][] = 'Max 30 lastname chars are accepted.';
                return FALSE;        
            }        
        }
        
        if(isset($data['fields']['email']))
        {
            if(!is_string($data['fields']['email']))
            {
                throw new SmartModelException('"name" isnt from type string');                     
            }
            
            if( empty($data['fields']['email']) )
            {
                $data['error'][] = 'Email entry is empty!';
                return FALSE;        
            } 

            $str_len = strlen( $data['fields']['email'] );
            if( $str_len > 500 )
            {
                $data['error'][] = 'Max 500 email chars are accepted.';
                return FALSE;         
            }  
            
            if( !@preg_match("/^[a-zA-Z0-9_.+-]+@[^@]+[^@.]\.[a-zA-Z]{2,}$/", $data['fields']['email']) )
            {
                $data['error'][] = 'Email entry is not correct!';
                return FALSE;        
            }            
        }
        
        if(isset($data['fields']['media_folder']))
        {
            if(!is_string($data['fields']['media_folder']))
            {
                throw new SmartModelException('"media_folder" isnt from type string');                     
            }
            
            if( @preg_match("/[^0-9-]/", $data['fields']['media_folder']) )
            {
                throw new SmartModelException('Wrong media folder value: '.$data['fields']['media_folder']);         
            }             
        }        
        
        if(isset($data['fields']['status']))
        {
            if(!is_int($data['fields']['status']))
            {
                throw new SmartModelException('"status" isnt from type int');                     
            }
            
            if(($data['fields']['status'] != 1) && ($data['fields']['status'] != 2))
            {
                throw new SmartModelException('Wrong status value: '.$data['fields']['status']);          
            }         
        }

        if(isset($data['fields']['role']))
        {
            if(!is_int($data['fields']['role']))
            {
                throw new SmartModelException('"role" isnt from type int');                     
            }               
            elseif(($data['fields']['role'] < 10) || ($data['fields']['role'] > 100))
            {
                $data['error'][] = 'Rights value must be between 10 an 100';
                return FALSE;        
            }         
        }

        if( !is_int($data['id_user']) )
        {
            throw new SmartModelException('"id_user" isnt from type int');         
        } 
        
        // Check if id_user exists
        if($this->userExists($data['id_user']) == FALSE)
        {
            throw new SmartModelException('id_user dosent exists: '.$data['id_user']); 
        }    
        
        return TRUE;
    }
    
    /**
     * check if id_user exists
     *
     * @param int $id_user User id
     * @return bool
     */    
    function userExists( $id_user )
    {
        
        $sql = "
            SELECT
                id_user
            FROM
                {$this->config['dbTablePrefix']}user_user
            WHERE
                id_user=$id_user";
        
        $result = $this->model->dba->query($sql);

        if($result->numRows() > 0)
        {
            return TRUE;
        }
        
        return FALSE;    
    } 
    
}

?>
