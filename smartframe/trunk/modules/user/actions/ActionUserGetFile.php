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
 * ctionUserGetFile class 
 *
 */
 
class ActionUserGetFile extends SmartAction
{
    private $tblFields_pic = array('id_user' => TRUE,
                                   'id_file' => TRUE,
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
                {$this->config['dbTablePrefix']}user_media_file
            WHERE
                `id_file`= {$data['id_file']}";

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

        if(!isset($data['id_file']))
        {
            throw new SmartModelException("No 'id_file' defined");
        }

        if(preg_match("/[^0-9]/",$data['id_file']))
        {
            throw new SmartModelException("'id_file' isnt numeric");
        }
        return TRUE;
    }
}

?>
