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
     * Array of user_config table fields and the format/allowed values of each
     */
    private $tblFields = array('force_format'   => array('Int',array(0,1,2)),
                               'default_format' => array('Int',array(1,2)));
                               
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
            $methode = 'set'.$this->tblFields[$key][0];
            $stmt->$methode($val);
        }
       
        $stmt->execute();
       
        return TRUE;
    }
    
    /**
     * validate user data
     *
     * @param array $data
     * @return bool 
     */    
    function validate( $data = FALSE )
    {
        foreach($data as $key => $val)
        {
            // check if database fields exists
            if(!isset($this->tblFields[$key]))
            {
                throw new SmartModelException("user_config table field '".$key."' dosent exists!");
            }
            // check if allowed database field and format are ok
            elseif(!in_array($val, $this->tblFields[$key][1], TRUE))
            {
                throw new SmartModelException("The value '{$val}' isnt allowed for the table field '{$key}' of the 'user_config' db table");            
            }
        }

        return TRUE;
    }
}

?>
