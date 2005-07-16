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
 * ActionNavigationReorderRank class 
 *
 */

class ActionNavigationReorderRank extends SmartAction
{
    /**
     * get navigation node data
     *
     * @param array $data
     * @return bool true or false on error
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
        if(!isset($data['id_parent']))
        {
            throw new SmartModelException('id_parent isnt defined');        
        }
        
        if(preg_match("/[^0-9]/",$data['id_parent']))
        {
            throw new SmartModelException('Wrong id_parent format: '.$data['id_parent']);        
        }

        return TRUE;
    }
}

?>
