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
 * ActionArticleGetLastRank class 
 *
 * USAGE:
 *
 * $model->action('article','getLastRank',
 *                array('id_node'   => int,
 *                      'result'    => & int ));
 */

class ActionArticleGetLastRank extends SmartAction
{
    /**
     * get last rank of articles of a given id_node
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $sql = "
            SELECT
                a.`rank`
            FROM
                {$this->config['dbTablePrefix']}article_article AS a,
                {$this->config['dbTablePrefix']}article_node_rel AS r
            WHERE
                r.`id_node`={$data['id_node']}
            AND
                r.`id_article`=a.`id_article`
            ORDER BY a.`rank` DESC
            LIMIT 1";
        
        $rs = $this->model->dba->query($sql);
        $row = $rs->fetchAssoc(); 
        if(isset($row['rank']))
        {
            $data['result'] = $row['rank'];
        }
        else
        {
            $data['result'] = FALSE;
        }
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool
     */    
    public function validate( $data = FALSE )
    { 
        if(!isset($data['id_node']))
        {
            throw new SmartModelException('id_node isnt defined');        
        }
        
        if(!is_int($data['id_node']))
        {
            throw new SmartModelException('id_node isnt from type int');        
        }

        if(!isset($data['result']))
        {
            throw new SmartModelException('Missing "result" var reference'); 
        }       

        return TRUE;
    }
}

?>
