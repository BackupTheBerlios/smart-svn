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
                            
    private $userRole = array('10' => 'Superuser',
                              '20' => 'Administrator',
                              '40' => 'Editor',
                              '60' => 'Author',
                              '80' => 'Contributor',
                              '100' => 'Webuser');
    
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
            ORDER BY
                {$_order}";
        
        $rs = $this->model->db->executeQuery($sql, ResultSet::FETCHMODE_ASSOC);
        
        while($rs->next())
        {
            $tmp = array();
            foreach ($data['fields'] as $f)
            {
                $methode = 'get'.$this->tblFields_user[$f];
                $tmp[$f] = $rs->$methode($f);
            }  
            
            if(isset($data['translate_role']) && isset($tmp['role']))
            {
                $tmp['role_t'] = $this->userRole[$tmp['role']];    
            }
            
            $data['result'][] = $tmp;
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
        
        return TRUE;
    }
}

?>
