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
 * ActionOptionsUpdateConfigOptions class 
 *
 */
 
class ActionOptionsUpdateConfigOptions extends SmartAction
{
    protected $tblFields_config = 
                      array('templates_folder' => 'String',
                            'views_folder'     => 'String',
                            'disable_cache'    => 'Int',
                            'rejected_files'   => 'String');
    /**
     * update common config values
     *
     * @param array $data
     * @return bool true or false on error
     */
    function perform( $data = FALSE )
    {
        // id no fields defined do nothing
        if(!is_array($data['fields']) || (count($data['fields']) == 0))
        {
            return TRUE;
        }
        
        $comma = '';
        $fields = '';
        
        foreach($data['fields'] as $key => $val)
        {
            $fields .= $comma.'`'.$key.'`=?';
            $comma = ',';
        }
        
        $sql = "
            UPDATE {$this->config['dbTablePrefix']}common_config
                SET
                   $fields";
        
        $stmt = $this->model->dba->prepare($sql);                    
        
        foreach($data['fields'] as $key => $val)
        {
            $methode = 'set'.$this->tblFields_config[$key];
            $stmt->$methode($val);
        }
       
        $stmt->execute();           
        
        return TRUE;
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true or false on error
     */    
    public function validate( $data = FALSE )
    { 
        // check if database fields exists
        foreach($data['fields'] as $key => $val)
        {
            if(!isset($this->tblFields_config[$key]))
            {
                throw new SmartModelException("Field '".$key."' dosent exists!");
            }
        }
        
        return TRUE;
    }
}

?>
