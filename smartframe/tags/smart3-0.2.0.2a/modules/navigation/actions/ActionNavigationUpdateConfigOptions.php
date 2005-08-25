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
 * ActionNavigationUpdateConfigOptions class 
 *
 * $model->action('navigation','updateConfigOptions',
 *                array('thumb_width'    => 'Int',
 *                      'img_size_max'   => 'Int',
 *                      'file_size_max'  => 'Int',
 *                      'force_format'   => 'Int',
 *                      'default_format' => 'Int',
 *                      'use_short_text' => 'Int',
 *                      'use_body'       => 'Int',
 *                      'use_logo'       => 'Int',
 *                      'use_images'     => 'Int',
 *                      'use_files'      => 'Int') )
 */
 
class ActionNavigationUpdateConfigOptions extends SmartAction
{
    protected $tblFields_config = 
                      array('thumb_width'    => 'Int',
                            'img_size_max'   => 'Int',
                            'file_size_max'  => 'Int',
                            'force_format'   => 'Int',
                            'default_format' => 'Int',
                            'use_short_text' => 'Int',
                            'use_body'       => 'Int',
                            'use_logo'       => 'Int',
                            'use_images'     => 'Int',
                            'use_files'      => 'Int');
    /**
     * update navigation config values
     *
     * @param array $data
     * @return bool true or false on error
     */
    function perform( $data = FALSE )
    {
        $comma = '';
        $fields = '';
        
        foreach($data['fields'] as $key => $val)
        {
            $fields .= $comma.'`'.$key.'`=?';
            $comma = ',';
        }
        
        $sql = "
            UPDATE {$this->config['dbTablePrefix']}navigation_config
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
     * @return bool
     */    
    public function validate( $data = FALSE )
    { 
        if(!isset($data['fields']) || !is_array($data['fields']) || (count($data['fields'])<1))
        {
            throw new SmartModelException("Array key 'fields' dosent exists, isnt an array or is empty!");
        }
        
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
