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
 * ActionMiscGetKeywordIds
 *
 * get misc text related keyword Id's
 *
 * USAGE:
 *
 * $model->action('misc', 'getKeywordIds',
 *                array('result'  => &$array(),
 *                      'id_text' => int,
 *                      'key_status' => array('>|<|=|>=|<=|!=',1|2) // keyword status - optional
 *                      ) );
 * 
 */

class ActionMiscGetKeywordIds extends SmartAction
{                                           
    /**
     * get misc text related keywords
     *
     */
    public function perform( $data = FALSE )
    { 
        if(isset($data['key_status']))
        {
            $sql_key_status  = " AND mk.`id_key`=k.`id_key`";
            $sql_key_status .= " AND k.`status`{$data['key_status'][0]}{$data['key_status'][1]}";
            $sql_key_table   = ",{$this->config['dbTablePrefix']}keyword AS k";
        }
        else
        {
            $sql_key_status = "";
            $sql_key_table  = "";
        }
        
        $sql = "SELECT SQL_CACHE
                  mk.`id_key`
                FROM 
                  {$this->config['dbTablePrefix']}misc_keyword AS mk
                  {$sql_key_table}
                WHERE
                   mk.`id_text`={$data['id_text']}
                   {$sql_key_status}";

        $result = $this->model->dba->query($sql);  
        while($row = $result->fetchAssoc())
        {
            $data['result'][] = (int)$row['id_key'];
        }         
    } 
    
    /**
     * validate array data
     *
     */    
    public function validate( $data = FALSE )
    {
        if(!isset($data['id_text'])) 
        {
            throw new SmartModelException("'id_text' isnt defined");
        }
        elseif(!is_int($data['id_text']))
        {
            throw new SmartModelException("'id_text' isnt from type int");
        }         

        if(isset($data['key_status']))
        {
            if(!is_array($data['key_status']))
            {
                throw new SmartModelException('"key_status" isnt an array'); 
            }
            else
            {
                if(!preg_match("/>|<|=|>=|<=|!=/",$data['key_status'][0]))
                {
                    throw new SmartModelException('Wrong "key_status" array[0] value: '.$data['status'][0]); 
                }

                if(!isset($data['key_status'][1]) || preg_match("/[^0-9]+/",$data['key_status'][1]))
                {
                    throw new SmartModelException('Wrong "key_status" array[1] value: '.$data['key_status'][1]); 
                }
            }
        }

        return TRUE;
    }  
}

?>