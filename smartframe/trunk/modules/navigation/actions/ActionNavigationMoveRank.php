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
 * ActionNavigationUpdateNode class 
 *
 */
 
class ActionNavigationMoveRank extends SmartAction
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
                                   'id_node' => $data['id_node'],
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

        if(($data['dir'] != 'up') && ($data['dir'] != 'down'))
        {
            throw new SmartModelException('Wrong "dir" action data var: '.$data['dir']); 
        }

        if(!isset($data['id_node']))
        {
            throw new SmartModelException('"id_node" action data var isnt defined');   
        }

        if(preg_match("/[^0-9]/",$data['id_node']))
        {
            throw new SmartModelException('Wrong id_node format: '.$id_user);        
        }
        
        return TRUE;
    }
    
    private function moveRankUp( $node )
    {
        if( $node['rank'] == 0 )
        {
            return;
        }
        
        $nextNode = $this->getNextIdNode( $node['id_parent'], $node['$rank'] - 1 );
        
        $this->model->action('navigation','updateNode',
                             array('id_node' => $node['id_node'],
                                   'fields'  => array('rank' => $node['$rank'] - 1)));

        $this->model->action('navigation','updateNode',
                             array('id_node' => $nextNode['id_node'],
                                   'fields'  => array('rank' => $nextNode['$rank'] + 1)));

    }

    private function moveRankDown( $node )
    {        
        $nextNode = $this->getNextIdNode( $node['id_parent'], $node['$rank'] + 1 );
        
        if(!isset($nextNode['id_node']))
        {
            return;
        }
        
        $this->model->action('navigation','updateNode',
                             array('id_node' => $node['id_node'],
                                   'fields'  => array('rank' => $node['$rank'] + 1)));

        $this->model->action('navigation','updateNode',
                             array('id_node' => $nextNode['id_node'],
                                   'fields'  => array('rank' => $nextNode['$rank'] - 1)));

    }
    
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
