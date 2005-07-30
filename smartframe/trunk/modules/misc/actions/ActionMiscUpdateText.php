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
 * ActionMiscUpdateText class 
 *
 */
 
class ActionMiscUpdateText extends SmartAction
{
    /**
     * Fields and the format of each of the db table
     *
     */
    protected $tblFields_text = 
                      array('id_text'      => 'Int',
                            'status'       => 'Int',
                            'format'       => 'Int',
                            'media_folder' => 'String',
                            'title'        => 'String',
                            'description'  => 'String',
                            'body'         => 'String');
    /**
     * update navigation node
     *
     * @param array $data
     * @return bool true or false on error
     */
    function perform( $data = FALSE )
    {
        $comma = '';
        $fields = '';
        
        foreach($data['fields'] as $key => $val)
        {
            $fields .= $comma.'`'.$key.'`=?';
            $comma = ',';
        }
        
        $sql = "
            UPDATE {$this->config['dbTablePrefix']}misc_text
                SET
                   $fields
                WHERE
                `id_text`={$data['id_text']}";
        
        $stmt = $this->model->dba->prepare($sql);                    
        
        foreach($data['fields'] as $key => $val)
        {
            $methode = 'set'.$this->tblFields_text[$key];
            $stmt->$methode($val);
        }
       
        $stmt->execute();           
        
        return TRUE;
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true or false on error
     */    
    public function validate( $data = FALSE )
    { 
        // check if database fields exists
        foreach($data['fields'] as $key => $val)
        {
            if(!isset($this->tblFields_text[$key]))
            {
                throw new SmartModelException("Field '".$key."' dosent exists!");
            }
        }

        if(preg_match("/[^0-9]/",$data['id_text']))
        {
            throw new SmartModelException('Wrong id_text format: '.$id_text);        
        }
        
        return TRUE;
    }
}

?>
