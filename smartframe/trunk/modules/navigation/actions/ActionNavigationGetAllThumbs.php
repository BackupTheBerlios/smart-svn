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
 * ActionNavigationGetAllThumbs
 *
 */
 
class ActionNavigationGetAllThumbs extends SmartAction
{
    private $tblFields_pic = array('id_pic'  => TRUE,
                                   'id_node' => TRUE,
                                   'rank'    => TRUE,
                                   'file'    => TRUE,
                                   'title'   => TRUE,
                                   'description' => TRUE,
                                   'mime'    => TRUE,
                                   'size'    => TRUE);
    /**
     * get data of all users
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {
        $comma = '';
        $_fields = '';
        foreach ($data['fields'] as $f)
        {
            $_fields .= $comma.'`'.$f.'`';
            $comma = ',';
        }

        $sql = "
            SELECT
                {$_fields}
            FROM
                {$this->config['dbTablePrefix']}navigation_media_pic
            WHERE
                (`id_node`={$data['id_node']})
            ORDER BY
                `rank` ASC";

        $rs = $this->model->dba->query($sql);
        
        $data['result'] = array();
        
        while($row = $rs->fetchAssoc())
        {            
            $data['result'][] = $row;
        } 
        
        return TRUE;
    } 
    
    public function validate( $data = FALSE )
    {
        foreach($data['fields'] as $key)
        {
            if(!isset($this->tblFields_pic[$key]))
            {
                throw new SmartModelException("Field '".$key."' dosent exists!");
            }
        }

        if(!isset($data['id_node']))
        {
            throw new SmartModelException("No 'id_node' defined");
        }

        return TRUE;
    }
}

?>