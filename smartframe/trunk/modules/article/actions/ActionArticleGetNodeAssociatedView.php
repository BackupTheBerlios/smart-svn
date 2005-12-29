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
 * ActionArticleGetNodeAssociatedView class 
 *
 * USAGE:
 * $model->action( 'article', 'getNodeAssociatedView',
 *                 array('id_node' => int,
 *                       'result'  => & array ));
 *
 *
 */
 
class ActionArticleGetNodeAssociatedView extends SmartAction
{
    /**
     * get article node related view
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {       
        $sql = "
            SELECT
                v.`name`,
                v.`id_view`
            FROM
                {$this->config['dbTablePrefix']}article_node_view_rel AS an,
                {$this->config['dbTablePrefix']}article_view AS v
            WHERE
                an.`id_node`={$data['id_node']} 
            AND
                an.`id_view`=v.`id_view`";

        $rs = $this->model->dba->query($sql);
       
        if( $rs->numRows() > 0 )
        {
            $data['result'] = $rs->fetchAssoc();
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

        if(!is_int($data['id_node']))
        {
            return FALSE;
        }

        if(!isset($data['result']))
        {
            throw new SmartModelException('Missing "result" array var: '); 
        }
        
        return TRUE;
    }
}

?>
