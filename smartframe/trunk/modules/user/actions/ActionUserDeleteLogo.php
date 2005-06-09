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
 * ActionUserDeleteLogo class 
 *
 */

class ActionUserDeleteLogo extends SmartAction
{
    /**
     * Delete user logo
     *
     * param:
     * data['id_user']
     *
     * @param array $data
     * @return bool
     */
    public function perform( $data = FALSE )
    {
        $_file = '';

        $this->model->action('user',
                             'getUser',
                             array('result'  => & $_file,
                                   'id_user' => $data['id_user'],
                                   'fields'  => array('logo','media_folder')));   

        if(!@unlink(SMART_BASE_DIR . 'data/user/'.$_file['media_folder'].'/'.$_file['logo']))
        {
            throw new SmartModelException('Cant delete user logo: data/user/'.$_file['media_folder'].'/'.$_file['logo']);
        }
                            
        $this->model->action('user',
                             'update',
                             array('error'   => & $data['error'],
                                   'id_user' => $data['id_user'],
                                   'user'    => array('logo' => '')));
        
        return TRUE;
    }
    
    /**
     * validate the parameters passed in the data array
     *
     * @param array $data
     * @return bool
     */    
    public function validate(  $data = FALSE  )
    {
        if(preg_match("/[^0-9]/",$data['id_user']))
        {
            throw new SmartModelException('Wrong id_user format: '.$id_user);        
        }
        
        if(FALSE == $this->userExists( $data['id_user'] ))
        {
            throw new SmartModelException('id_user dosent exists: '.$id_user);  
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
                id_user='$id_user'";
        
        $result = $this->model->dba->query($sql);

        if($result->numRows() == 1)
        {
            return TRUE;
        }
        
        return FALSE;    
    }     
}

?>