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
 * ActionNavigationRelatedView class 
 *
 */
 
class ActionNavigationRelatedView extends SmartAction
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
                v.`name`
            FROM
                {$this->config['dbTablePrefix']}navigation_node AS n,
                {$this->config['dbTablePrefix']}navigation_view AS v
            WHERE
                n.`id_node`={$data['id']} 
            AND
                n.`id_view`=v.`id_view`";
        
        $rs = $this->model->dba->query($sql);
        $row = $rs->fetchAssoc();

        if(isset($row['name']))
        {
           $data['result'] = $row['name'];
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
        if(preg_match("/[^0-9]/",$data['id']))
        {
            throw new SmartModelException('Wrong "id" format: '.$data['id']);        
        }

        if(!isset($data['result']))
        {
            throw new SmartModelException('Missing "result" array var: '); 
        }

        return TRUE;
    }
}

?>
