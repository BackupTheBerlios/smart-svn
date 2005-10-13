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
 * ActionNavigationReorderRank class 
 *
 * USAGE;
 * $model->action('article','reorderRank',
 *                array('id_node' => int) )
 *
 */

class ActionArticleReorderRank extends SmartAction
{
    /**
     * reorder article ranks of an id_node
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $sql = "
            SELECT
                `id_article`
            FROM
                {$this->config['dbTablePrefix']}article_article
            WHERE
                `id_node`={$data['id_node']} 
            ORDER BY `rank` ASC";
        
        $rs = $this->model->dba->query($sql);
        
        $rank = 0;

        while($row = $rs->fetchAssoc())
        {
            $sql = "UPDATE {$this->config['dbTablePrefix']}article_article
                    SET `rank`={$rank}
                    WHERE
                        `id_article`={$row['id_article']}";  
            $this->model->dba->query($sql);
            $rank++;
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
            throw new SmartModelException('"id_node" isnt defined');        
        }
        
        if(!is_int($data['id_node']))
        {
            throw new SmartModelException('"id_node" isnt from type int');        
        }

        return TRUE;
    }
}

?>
