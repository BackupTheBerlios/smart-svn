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
 
class ActionUserGetUsers extends ActionUser
{
    /**
     * get data of all users
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {
        $comma = '';
        $_fields = '';
        foreach ($data['fields'] as $f)
        {
            $_fields .= $comma.'`'.$f.'`';
            $comma = ',';
        }
        
        if(isset($data['order']))
        {
            $_order = $data['order'];
        }
        else
        {
            $_order = '`role` ASC, `login` ASC';
        }
        
        $sql = "
            SELECT
                {$_fields}
            FROM
                {$this->config['dbTablePrefix']}user_user
            WHERE
                (`role`{$data['only_role']})
            OR
                (`id_user`={$data['and_id_user']})
            ORDER BY
                {$_order}";

        $rs = $this->model->dba->query($sql);
        
        $data['result'] = array();
        
        while($row = $rs->fetchAssoc())
        {            
            if(isset($data['translate_role']) && isset($row['role']))
            {
                $row['role_t'] = $this->userRole[$row['role']];    
            }
            
            $data['result'][] = $row;
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

        if(!isset($data['only_role']))
        {
            throw new SmartModelException("'only_role' param required");
        }

        if(!isset($data['and_id_user']))
        {
            throw new SmartModelException("'and_id_user' param required");
        }
        
        return TRUE;
    }
}

?>
