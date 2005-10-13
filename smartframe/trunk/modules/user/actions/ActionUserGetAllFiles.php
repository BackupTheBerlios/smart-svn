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
 * ActionUserGetAllFiles class 
 *
 *
 * USAGE:
 *
 * $model->action('user','getAllFiles',
 *                array('id_user' => int, 
 *                      'result'  => & array, 
 *                      'fields'  => array('id_file','rank','file',
 *                                         'title','description',
 *                                         'mime','size')))
 *
 */
 
class ActionUserGetAllFiles extends SmartAction
{
    // allowed fields
    private $tblFields_pic = array('id_file' => TRUE,
                                   'rank'   => TRUE,
                                   'file'   => TRUE,
                                   'title'  => TRUE,
                                   'description' => TRUE,
                                   'mime'   => TRUE,
                                   'size'   => TRUE);
    /**
     * get file data from an users
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
                (`id_user`={$data['id_user']})
            ORDER BY
                `rank` ASC";

        $rs = $this->model->dba->query($sql);
        
        while($row = $rs->fetchAssoc())
        {            
            $data['result'][] = $row;
        } 
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

        if(!isset($data['result']))
        {
            throw new SmartModelException("'result' isnt set");
        }
        elseif(!is_array($data['result']))
        {
            throw new SmartModelException("'result' isnt from type array");
        }

        if(!isset($data['id_user']))
        {
            throw new SmartModelException("No 'id_user' defined");
        }

        if(!is_int($data['id_user']))
        {
            throw new SmartModelException("'id_user' isnt from type int");
        }

        return TRUE;
    }
}

?>
