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
 * ActionUserUpdateOptions class 
 *
 */
 
class ActionUserUpdateOptions extends smartAction
{
    /**
     * Array of user_config table fields and the format of each
     */
    private $tblFields = array('force_format'   => 'Int',
                               'default_format' => 'Int');
                               
    /**
     * update user module options
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    { 
        
        $comma = '';
        $fields = '';
        
        foreach($data as $key => $val)
        {
            $fields .= $comma.'`'.$key.'`=?';
            $comma = ',';
        }
        
        $sql = "UPDATE {$this->config['dbTablePrefix']}user_config SET $fields";

        $stmt = $this->model->dba->prepare($sql);                    
        
        foreach($data as $key => $val)
        {
            $methode = 'set'.$this->tblFields[$key];
            $stmt->$methode($val);
        }
       
        $stmt->execute();
       
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
        foreach($data as $key => $val)
        {
            if(!isset($this->tblFields[$key]))
            {
                throw new SmartModelException("user_config table field '".$key."' dosent exists!");
            }
        }

        return TRUE;
    }
}

?>
