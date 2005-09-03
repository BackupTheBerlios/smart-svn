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
 * ActionNavigationMoveNodeRank class 
 *
 * USAGE:
 *
 * $model->action('navigarion','moveNodeRank',
 *                array('id_node' => int,    
 *                      'dir'     => string)) // 'up' or 'down'
 *
 */
 
class ActionNavigationMoveNodeRank extends SmartAction
{
    /**
     * exchange (move) navigation node rank
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $node = array();
        $this->model->action('navigation','getNode',
                             array('result'  => &$node,
                                   'id_node' => (int)$data['id_node'],
                                   'fields'  => array('id_node','id_parent','rank')));        
        
        if($data['dir'] == 'up')
        {
            $this->moveRankUp( $node );        
        }
        else
        {
            $this->moveRankDown( $node ); 
        }
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true or false on error
     */    
    public function validate( $data = FALSE )
    { 
        if(!isset($data['dir']))
        {
            throw new SmartModelException('"dir" action data var isnt defined');   
        }
        elseif(!is_string($data['dir']))
        {
            throw new SmartModelException('"dir" isnt from type string');   
        }
        
        if(($data['dir'] != 'up') && ($data['dir'] != 'down'))
        {
            throw new SmartModelException('Wrong "dir" action data var: '.$data['dir']); 
        }

        if(!isset($data['id_node']))
        {
            throw new SmartModelException('"id_node" action data var isnt defined');   
        }

        if(!is_int($data['id_node']))
        {
            throw new SmartModelException('"id_node" isnt from type int');        
        }
        
        return TRUE;
    }
    /**
     * move rank up of a node
     *
     * @param array $node
     */      
    private function moveRankUp( &$node )
    {
        // rank position 0 cant be moved any more up
        if( $node['rank'] == 0 )
        {
            return;
        }
        
        // get the next upper rank node
        $nextNode = $this->getNextIdNode( $node['id_parent'], $node['rank'] - 1 );
        
        // exchange both node ranks
        
        $this->model->action('navigation','updateNode',
                             array('id_node' => (int)$node['id_node'],
                                   'fields'  => array('rank' => $node['rank'] - 1)));

        $this->model->action('navigation','updateNode',
                             array('id_node' => (int)$nextNode['id_node'],
                                   'fields'  => array('rank' => $nextNode['rank'] + 1)));

    }
    /**
     * move rank down of a node
     *
     * @param array $node
     */  
    private function moveRankDown( &$node )
    {        
        // get the next downer rank node
        $nextNode = $this->getNextIdNode( $node['id_parent'], $node['rank'] + 1 );
        
        // if we are at the end return
        if(!isset($nextNode['id_node']))
        {
            return;
        }
        
        // exchange both node ranks
        
        $this->model->action('navigation','updateNode',
                             array('id_node' => (int)$node['id_node'],
                                   'fields'  => array('rank' => $node['rank'] + 1)));

        $this->model->action('navigation','updateNode',
                             array('id_node' => (int)$nextNode['id_node'],
                                   'fields'  => array('rank' => $nextNode['rank'] - 1)));

    }
    
    /**
     * get id_node and rank of a node with a specific rank and id_parent
     *
     * @param int $id_parent
     * @param int $rank
     */      
    private function getNextIdNode( $id_parent, $rank )
    {
        $sql = "
            SELECT
                `id_node`,
                `rank`
            FROM
                {$this->config['dbTablePrefix']}navigation_node
            WHERE
                `id_parent`={$id_parent} 
            AND
                `rank`={$rank}";
        
        $rs = $this->model->dba->query($sql);
        return $rs->fetchAssoc();   
    }
}

?>
