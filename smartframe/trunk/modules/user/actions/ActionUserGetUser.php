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
 * ActionUserGetUsers class 
 *
 */

include_once(SMART_BASE_DIR . 'modules/user/includes/ActionUser.php');
 
class ActionUserGetUser extends ActionUser
{
    /**
     * update user data
     *
     * @param array $data
     * @return bool true or false on error
     */
    function perform( $data = FALSE )
    {
        $comma = '';
        $_fields = '';
        foreach ($data['fields'] as $f)
        {
            $_fields .= $comma.'`'.$f.'`';
            $comma = ',';
        }
        
        $sql = "
            SELECT
                {$_fields}
            FROM
                {$this->config['dbTablePrefix']}user_user
            WHERE
                `id_user`={$data['id_user']}";
        
        $rs = $this->model->dba->query($sql);
        
        $row = $rs->fetchAssoc();

        $data['result'] = array();

        foreach ($data['fields'] as $f)
        {
            $data['result'][$f] = $row[$f];
        }  
            
        if(isset($data['translate_role']) && isset($data['result']['role']))
        {
            $data['result']['role_t'] = $this->userRole[$data['result']['role']];    
        }
        
        return TRUE;
    } 
    
    public function validate( $data = FALSE )
    {
        foreach($data['fields'] as $key)
        {
            if(!isset($this->tblFields_user[$key]))
            {
                throw new SmartModelException("Field '".$key."' dosent exists!");
            }
        }

        if(preg_match("/[^0-9]/",$data['id_user']))
        {
            throw new SmartModelException('Wrong id_user format: '.$id_user);        
        }
        
        return TRUE;
    }
}

?>
