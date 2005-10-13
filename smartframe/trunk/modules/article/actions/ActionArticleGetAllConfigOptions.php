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
 * ActionArticleGetAllConfigOptions  class 
 *
 * USAGE:
 *
 * $model->action('article','getAllConfigOptions',
 *                array('result' => & array));
 */
 
class ActionArticleGetAllConfigOptions extends SmartAction
{
    /**
     * get all article module config options
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {
        $sql = "SELECT SQL_CACHE * FROM {$this->config['dbTablePrefix']}article_config";

        $rs = $this->model->dba->query($sql);
        
        $data['result'] = $rs->fetchAssoc();
        
        return TRUE;
    } 
    
    public function validate( $data = FALSE )
    {
        if(!isset($data['result']))
        {
            throw new SmartModelException("No 'result' defined");
        }

        return TRUE;
    }
}

?>
