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
 * ActionArticleGetAllThumbs
 *
 * USAGE:
 *
 * $model->action('article','getAllThumbs',
 *                array('id_article' => int, 
 *                      'result'     => & array,
 *                      'fields'     => array('id_pic','rank','file',
 *                                            'title','description',
 *                                            'mime','size','height','width')))
 *
 */
 
class ActionArticleGetAllThumbs extends SmartAction
{
    private $tblFields_pic = array('id_pic'      => TRUE,
                                   'id_article'  => TRUE,
                                   'rank'        => TRUE,
                                   'file'        => TRUE,
                                   'width'       => TRUE,
                                   'height'      => TRUE,
                                   'title'       => TRUE,
                                   'description' => TRUE,
                                   'mime'        => TRUE,
                                   'size'        => TRUE);
    /**
     * get all picture thumbnail data of a node
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
            SELECT SQL_CACHE
                {$_fields}
            FROM
                {$this->config['dbTablePrefix']}article_media_pic
            WHERE
                (`id_article`={$data['id_article']})
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
        if(!isset($data['fields']))
        {
            throw new SmartModelException("'fields' isnt set");
        }
        elseif(!is_array($data['fields']))
        {
            throw new SmartModelException("'fields' isnt from type array");
        }
        elseif(count($data['fields']) == 0)
        {
            throw new SmartModelException("'fields' array is empty");
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

        if(!isset($data['id_article']))
        {
            throw new SmartModelException("No 'id_article' defined");
        }
        if(!is_int($data['id_article']))
        {
            throw new SmartModelException("'id_article' isnt from type int");
        }

        return TRUE;
    }
}

?>
