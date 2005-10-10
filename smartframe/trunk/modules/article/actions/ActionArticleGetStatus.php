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
 * ActionNavigationGetNodeStatus class 
 *
 * USAGE:
 * $node_status = $model->action('navigation','getNodeStatus',
 *                               array('id_node' => int))
 *
 */
 
class ActionArticleGetStatus extends SmartAction
{   
    /**
     * get node status
     *
     * @param array $data
     * @return node status id or FALSE
     */
    function perform( $data = FALSE )
    {
        $sql = "
            SELECT SQL_CACHE
                n.status AS nodeStatus,
                a.status AS articleStatus
            FROM
                {$this->config['dbTablePrefix']}article_article as a,
                {$this->config['dbTablePrefix']}navigation_node as n
            WHERE
                a.`id_article`={$data['id_article']}
            AND
                a.`id_node`=n.`id_node`";
        
        $rs = $this->model->dba->query( $sql );  
        
        if( $row = $rs->fetchAssoc() )
        {
            $data['result'] = $row;
            return TRUE;
        }
        return FALSE;
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true or false on error
     */    
    public function validate( $data = FALSE )
    { 
        if(!isset($data['id_article']))
        {
            throw new SmartModelException('"id_article" isnt defined');        
        }
        if(!is_int($data['id_article']))
        {
            throw new SmartModelException('"id_article" isnt from type int');        
        }
        if(!isset($data['result']))
        {
            throw new SmartModelException('Missing "result" array var: '); 
        }
        elseif(!is_array($data['result']))
        {
            throw new SmartModelException('"result" isnt from type array'); 
        }           
        
        return TRUE;
    }
}

?>
