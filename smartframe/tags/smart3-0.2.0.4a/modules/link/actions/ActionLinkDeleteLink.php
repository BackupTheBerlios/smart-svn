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
     * delete link and navigation node relation
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {        
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}link_links
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
