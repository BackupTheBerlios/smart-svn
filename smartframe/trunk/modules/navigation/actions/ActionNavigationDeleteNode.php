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
 * ActionNavigationDeleteNode class 
 *
 * USAGE:
 *
 * $model->action('navigation','deleteNode',
 *                array('id_node'  => int))
 *
 */
 
class ActionNavigationDeleteNode extends SmartAction
{
    /**
     * delete navigation node and referenced table entries
     *
     * @param array $data
     * @return bool true or false on error
     */
    public function perform( $data = FALSE )
    {        
        $this->deleteSubNodes( $data['id_node'] );
        $this->deleteNode( $data['id_node'] );
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
        if(!isset($data['id_node']))
        {
            throw new SmartModelException('"id_node" isnt defined');        
        }    
        elseif(!is_int($data['id_node']))
        {
            throw new SmartModelException('"id_node" isnt from type int');        
        }

        return TRUE;
    }

    private function deleteNode( $id_node )
    {
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}navigation_node_lock
                  WHERE
                   `id_node`={$id_node}";

        $this->model->dba->query($sql);

        $sql = "DELETE FROM {$this->config['dbTablePrefix']}navigation_media_pic
                  WHERE
                   `id_node`={$id_node}";

        $this->model->dba->query($sql);

        $sql = "DELETE FROM {$this->config['dbTablePrefix']}navigation_media_file
                  WHERE
                   `id_node`={$id_node}";

        $this->model->dba->query($sql);

        $sql = "DELETE FROM {$this->config['dbTablePrefix']}navigation_index
                  WHERE
                   `id_node`={$id_node}";

        $this->model->dba->query($sql);

        $sql = "SELECT `media_folder` FROM {$this->config['dbTablePrefix']}navigation_node
                  WHERE
                   `id_node`={$id_node}";
                   
        $rs = $this->model->dba->query($sql);

        $row = $rs->fetchAssoc();

        if(isset($row['media_folder']) && !empty($row['media_folder']))
        {
            // delete user data media folder
            SmartCommonUtil::deleteDirTree( SMART_BASE_DIR.'data/navigation/'.$row['media_folder'] );
        }
        
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}navigation_node
                  WHERE
                   `id_node`={$id_node}";

        $this->model->dba->query($sql);
        
        // delete all node related content
        $this->model->broadcast('deleteNodeRelatedContent', 
                                array('id_node'   => (int)$id_node));   
        
           
    }
    
    private function deleteSubNodes( $id_node )
    {
        $tree = array();
        
        // get sub nodes
        $this->model->action('navigation','getTree', 
                             array('id_node'   => (int)$id_node,
                                   'result'    => & $tree,
                                   'fields'    => array('id_parent','status','id_node')));   
   
        if(count($tree) > 0)
        {
            foreach($tree as $node)
            {
                $this->deleteNode( $node['id_node'] );
            }
        }
    }
}

?>
