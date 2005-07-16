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
 * ActionUserGetUsers class 
 *
 */
 
class ActionUserGetPicture extends SmartAction
{
    private $tblFields_pic = array('id_node' => TRUE,
                                   'id_pic' => TRUE,
                                   'rank'   => TRUE,
                                   'file'   => TRUE,
                                   'description' => TRUE,
                                   'mime'   => TRUE,
                                   'size'   => TRUE);
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
                `id_pic`= {$data['id_pic']}";

        $rs = $this->model->dba->query($sql);
        
        $data['result'] = array();
        
        $data['result'] = $rs->fetchAssoc();
        
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

        if(!isset($data['id_pic']))
        {
            throw new SmartModelException("No 'id_pic' defined");
        }

        if(preg_match("/[^0-9]/",$data['id_pic']))
        {
            throw new SmartModelException("'id_pic' isnt numeric");
        }
        return TRUE;
    }
}

?>
