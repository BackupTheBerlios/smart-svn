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
 * ActionNavigationGetNode class 
 *
 * USAGE:
 *
 * $model->action('navigation','getLastRank',
 *                array('id_parent' => int,
 *                      'result'    => & int));
 */

class ActionNavigationGetLastRank extends SmartAction
{
    /**
     * get last rank of navigation child nodes of a given id_parent
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $sql = "
            SELECT
                `rank`
            FROM
                {$this->config['dbTablePrefix']}navigation_node
            WHERE
                `id_parent`={$data['id_parent']} 
            ORDER BY `rank` DESC
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
        if(!isset($data['id_parent']))
        {
            throw new SmartModelException('id_parent isnt defined');        
        }
        
        if(!is_int($data['id_parent']))
        {
            throw new SmartModelException('id_parent isnt from type int');        
        }

        if(!isset($data['result']))
        {
            throw new SmartModelException('Missing "result" var reference'); 
        }

        return TRUE;
    }
}

?>
