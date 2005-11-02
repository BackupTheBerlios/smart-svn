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
 * ActionLinkDeleteLink class 
 *
 * USAGE:
 *
 * $model->action('link','deleteLink',
 *                array('id_link'  => int))
 *
 */
 
class ActionLinkDeleteLink extends SmartAction
{
    /**
     * delete link and relations
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {        
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}link_links
                  WHERE
                   `id_link`={$data['id_link']}";

        $this->model->dba->query($sql);
        
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}link_keyword
                  WHERE
                   `id_link`={$data['id_link']}";

        $this->model->dba->query($sql);        
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true or false on error
     */    
    public function validate( $data = FALSE )
    {         
        if(!isset($data['id_link']))
        {
            throw new SmartModelException('"id_link" isnt defined');        
        }    
        elseif(!is_int($data['id_link']))
        {
            throw new SmartModelException('"id_link" isnt from type int');        
        }
        
        return TRUE;
    }
}

?>
