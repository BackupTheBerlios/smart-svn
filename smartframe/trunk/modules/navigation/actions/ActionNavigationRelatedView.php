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
 * USAGE:
 * $model->action( 'navigation', 'relatedView',
 *                 array('id_node' => int,
 *                       'result' => & string));
 *
 *
 */
 
class ActionNavigationRelatedView extends SmartAction
{
    /**
     * get navigation node related view
     *
     * @param array $data
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
                n.`id_node`={$data['id_node']} 
            AND
                n.`id_view`=v.`id_view`";

        $rs = $this->model->dba->query($sql);
        
        if( $row = $rs->fetchAssoc() )
        {
            $data['result'] = $row['name'];
        }
        else
        {
            $data['result'] = '';
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
            return FALSE;
        }

        return TRUE;
    }
}

?>
