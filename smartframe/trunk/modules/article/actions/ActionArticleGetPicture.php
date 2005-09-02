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
 * ActionArticleGetPicture class 
 *
 * USAGE:
 *
 * $model->action('article','getPicture',
 *                array('id_pic'  => int, 
 *                      'result'  => & array, 
 *                      'fields'  => array('id_pic','id_article','rank','file',
 *                                         'title','description','media_folder'
 *                                         'mime','size','width','height')))
 *
 */
 
class ActionArticleGetPicture extends SmartAction
{
    private $tblFields_pic = array('id_article'   => TRUE,
                                   'id_pic'       => TRUE,
                                   'rank'         => TRUE,
                                   'file'         => TRUE,
                                   'title'        => TRUE,
                                   'width'        => TRUE,
                                   'height'       => TRUE,
                                   'description'  => TRUE,
                                   'media_folder' => TRUE,
                                   'mime'         => TRUE,
                                   'size'         => TRUE);
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
            $_fields .= $comma.'m.`'.$f.'`';
            $comma = ',';
        }
        
        if(in_array('media_folder',$data['fields']))
        {
            $sel = $comma.'a.`media_folder`';
            $table = ",{$this->config['dbTablePrefix']}article_article AS a ";
            $where = " AND m.id_article=a.id_article";
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
                {$this->config['dbTablePrefix']}article_media_pic AS m
                {$table}
            WHERE
                m.`id_pic`={$data['id_pic']}
                {$where}";

        $rs = $this->model->dba->query($sql);
        
        if($rs->numRows() > 0)
        {
            $data['result'] = $rs->fetchAssoc();     
        }
    } 
    
    public function validate( $data = FALSE )
    {
        if(!isset($data['fields']) || !is_array($data['fields']) || (count($data['fields'])<1))
        {
            throw new SmartModelException("Array key 'fields' dosent exists, isnt an array or is empty!");
        }
        
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

        if(!isset($data['id_pic']))
        {
            throw new SmartModelException("No 'id_pic' defined");
        }

        if(!is_int($data['id_pic']))
        {
            throw new SmartModelException("'id_pic' isnt numeric");
        }

        if(isset($data['media_folder']) && !is_string($data['media_folder']))
        {
            throw new SmartModelException("'media_folder' isnt from type string");
        }
        
        return TRUE;
    }
}

?>
