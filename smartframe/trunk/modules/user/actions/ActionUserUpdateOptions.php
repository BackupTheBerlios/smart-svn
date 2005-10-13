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
 * ActionUserUpdateOptions class 
 *
 */
 
class ActionUserUpdateOptions extends smartAction
{
    /**
     * Array of user_config table fields and the format/allowed values of each
     */
    private $tblFields = array('file_size_max'  => 'Int',
                               'img_size_max'   => 'Int',
                               'force_format'   => 'Int',
                               'default_format' => 'Int',
                               'thumb_width'    => 'Int');
                               
    /**
     * update user module options
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    { 
        
        $comma  = "";
        $fields = "";
        
        foreach($data as $key => $val)
        {
            $fields .= $comma."`".$key."`='".$this->model->dba->escape($val)."'";
            $comma   = ",";
        }
        
        $sql = "UPDATE {$this->config['dbTablePrefix']}user_config SET $fields";

        $this->model->dba->query($sql);
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
        }

        return TRUE;
    }
}

?>
