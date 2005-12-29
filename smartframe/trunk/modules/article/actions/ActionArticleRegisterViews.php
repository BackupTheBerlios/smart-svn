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
 * ActionArticleRegisterViews
 *
 * USAGE:
 *
 * $model->action('article','registerViews',
 *                array('action'  => string,    // 'register' or 'unregister'
 *                      'id_view' => int,       // if action = 'unregister'
 *                      'name'    => string))   // if action = 'register'
 *
 */
 
class ActionArticleRegisterViews extends SmartAction
{
    /**
     * register/unregister a node related view
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {
        if($data['action'] == 'register')
        {
            $sql = "
                SELECT `id_view` FROM {$this->config['dbTablePrefix']}article_view
                 WHERE `name`='{$data['name']}'";

            $rs = $this->model->dba->query($sql);    
      
            if($rs->numRows() == 0)
            {    
                $sql = "
                    INSERT INTO {$this->config['dbTablePrefix']}article_view
                     (`name`)
                    VALUES
                     ('{$data['name']}')";

                $rs = $this->model->dba->query($sql);
            }
        }
        elseif($data['action'] == 'unregister')
        {
            $sql = "
                DELETE FROM {$this->config['dbTablePrefix']}article_view
                WHERE
                   `id_view`={$data['id_view']}";

            $this->model->dba->query($sql);
            
            $sql = "
                DELETE FROM {$this->config['dbTablePrefix']}article_node_view_rel
                WHERE
                   `id_view`={$data['id_view']}";

            $this->model->dba->query($sql);            
        }        
    } 
    
    public function validate( $data = FALSE )
    {
        if( !isset($data['action']) || !is_string($data['action']) )
        {        
            throw new SmartModelException ('"action" isnt defined or isnt from type string'); 
        }
        
        if(($data['action'] != 'register') && ($data['action'] != 'unregister'))
        {
            throw new SmartModelException ('"action" value must be "register" or "unregister"');         
        }

        if( ($data['action'] == 'register') && (!isset($data['name']) || empty($data['name'])))
        {
            throw new SmartModelException ('"name" isnt defined or is empty');                 
        }
        elseif( ($data['action'] == 'unregister') && (!isset($data['id_view']) || !is_int($data['id_view'])))
        {
            throw new SmartModelException ('"id_view" isnt defined or is not from type int');                 
        }        
        return TRUE;
    }
}

?>
