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
 * ActionArticleUpdateConfigOptions class 
 *
 * $model->action('article','updateConfigOptions',
 *                array('thumb_width'     => 'Int',
 *                      'img_size_max'    => 'Int',
 *                      'file_size_max'   => 'Int',
 *                      'force_format'    => 'Int',
 *                      'default_format'  => 'Int',
 *                      'default_order'     => 'String',
 *                      'default_ordertype' => 'String',
 *                      'use_article_view'  => 'Int',
 *                      'use_overtitle'   => 'Int',
 *                      'use_subtitle'    => 'Int',
 *                      'use_description' => 'Int',
 *                      'use_header'      => 'Int',
 *                      'use_ps'          => 'Int',
 *                      'use_logo'        => 'Int',
 *                      'use_images'      => 'Int',
 *                      'use_files'       => 'Int') )
 */
 
class ActionArticleUpdateConfigOptions extends SmartAction
{
    protected $tblFields_config = 
                      array('thumb_width'     => 'Int',
                            'img_size_max'    => 'Int',
                            'file_size_max'   => 'Int',
                            'force_format'    => 'Int',
                            'default_format'  => 'Int',
                            'default_order'     => 'String',
                            'default_ordertype' => 'String',
                            'use_article_view'  => 'Int',
                            'use_overtitle'   => 'Int',
                            'use_subtitle'    => 'Int',
                            'use_description' => 'Int',
                            'use_header'      => 'Int',
                            'use_ps'          => 'Int',
                            'use_changedate'  => 'Int',
                            'use_articledate' => 'Int',
                            'use_users'       => 'Int',
                            'use_keywords'    => 'Int',
                            'use_logo'        => 'Int',
                            'use_images'      => 'Int',
                            'use_files'       => 'Int');
    /**
     * update navigation config values
     *
     * @param array $data
     * @return bool true or false on error
     */
    function perform( $data = FALSE )
    {
        $comma  = "";
        $fields = "";
        
        foreach($data['fields'] as $key => $val)
        {
            $fields .= $comma."`".$key."`='".$this->model->dba->escape($val)."'";
            $comma = ",";
        }
        
        $sql = "
            UPDATE {$this->config['dbTablePrefix']}article_config
                SET
                   $fields";
        
        $this->model->dba->query($sql);                    
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
