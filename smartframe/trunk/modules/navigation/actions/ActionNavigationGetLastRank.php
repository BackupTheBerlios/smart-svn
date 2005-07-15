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
 * ActionNavigationGetNode class 
 *
 */

class ActionNavigationGetLastRank extends SmartAction
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
            $data['result'] = 0;
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

        if(!isset($data['result']))
        {
            throw new SmartModelException('Missing "result" array var: '); 
        }

        return TRUE;
    }
}

?>
