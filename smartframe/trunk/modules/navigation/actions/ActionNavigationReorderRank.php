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
 * $model->action('navigation','reorderRank',
 *                array('id_parent' => int) )
 *
 */

class ActionNavigationReorderRank extends SmartAction
{
    /**
     * reorder child navigation nodes of an id_parent
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $sql = "
            SELECT
                `id_node`
            FROM
                {$this->config['dbTablePrefix']}navigation_node
            WHERE
                `id_parent`={$data['id_parent']} 
            ORDER BY `rank` ASC";
        
        $rs = $this->model->dba->query($sql);
        
        $rank = 0;

        while($row = $rs->fetchAssoc())
        {
            $sql = "UPDATE {$this->config['dbTablePrefix']}navigation_node
                    SET `rank`={$rank}
                    WHERE
                        `id_node`={$row['id_node']}";  
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
        if(!isset($data['id_parent']))
        {
            throw new SmartModelException('"id_parent" isnt defined');        
        }
        
        if(!is_int($data['id_parent']))
        {
            throw new SmartModelException('"id_parent" isnt from type int');        
        }

        return TRUE;
    }
}

?>
