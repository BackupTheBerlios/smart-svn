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
 * ActionMiscGetPicture class 
 *
 * USAGE:
 *
 * $model->action('misc','getFile',
 *                array('id_file' => int, 
 *                      'result'  => & array, 
 *                      'fields'  => array('id_file','rank','file',
 *                                         'title','description',
 *                                         'mime','size')))
 *
 */
 
class ActionMiscGetFile extends SmartAction
{
    private $tblFields_pic = array('id_text' => TRUE,
                                   'id_file'  => TRUE,
                                   'rank'    => TRUE,
                                   'file'    => TRUE,
                                   'title'   => TRUE,
                                   'width'   => TRUE,
                                   'height'  => TRUE,
                                   'description'  => TRUE,
                                   'media_folder' => TRUE,
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
            if($f == 'media_folder')
            {
                continue;
            }
            $_fields .= $comma.'f.`'.$f.'`';
            $comma = ',';
        }
        
        if(in_array('media_folder',$data['fields']))
        {
            $sel = $comma.'t.`media_folder`';
            $table = ",{$this->config['dbTablePrefix']}misc_text AS t ";
            $where = " AND f.id_text=t.id_text";
        }
        else
        {
            $sel = '';
            $table = '';
            $where = '';
        }

        $sql = "
            SELECT
                {$_fields}
                {$sel}
            FROM
                {$this->config['dbTablePrefix']}misc_text_file AS f
                {$table}
            WHERE
                f.`id_file`= {$data['id_file']}
                {$where}";

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

        if(!isset($data['result']))
        {
            throw new SmartModelException("'result' isnt set");
        }
        elseif(!is_array($data['result']))
        {
            throw new SmartModelException("'result' isnt from type array");
        }

        if(!isset($data['id_file']))
        {
            throw new SmartModelException("No 'id_file' defined");
        }

        if(!is_int($data['id_file']))
        {
            throw new SmartModelException("'id_file' isnt numeric");
        }

        if(isset($data['media_folder']) && !is_string($data['media_folder']))
        {
            throw new SmartModelException("'media_folder' isnt from type string");
        }
        
        return TRUE;
    }
}

?>
