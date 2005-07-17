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
 * ActionNavigationUpdatePicture class 
 *
 */
class ActionNavigationUpdatePicture extends SmartAction
{
    /**
     * update/delete node picture from db table
     *
     * @param array $data
     * @return int user id or false on error
     */
    function perform( $data = FALSE )
    { 
        switch($data['action'])
        {
            case 'delete':
                    $this->delete($data);
                break;
            case 'update':
                    $this->update($data);
        }
        
        return TRUE;
    }
    
    /**
     * validate user data
     *
     * @param array $data User data
     * @return bool 
     */    
    function validate( $data = FALSE )
    {
        if(!isset($data['action']) || (($data['action'] != 'delete') && ($data['action'] != 'update')))
        {      
            throw new SmartModelException("No/Wrong 'action' defined. Required!");
        }
        
        if(!isset($data['id_pic']))
        {
            throw new SmartModelException("No 'id_pic' defined. Required!");
        }

        if(preg_match("/[^0-9]/",$data['id_pic']))
        {
            throw new SmartModelException("'id_pic' isnt numeric");
        }

        if($data['action'] == 'delete')
        {
            if(!isset($data['id_node']))
            {
                throw new SmartModelException("No 'id_node' defined. Required!");
            }

            if(preg_match("/[^0-9]/",$data['id_node']))
            {
                throw new SmartModelException("'id_node' isnt numeric");
            }
        }
        
        return TRUE;
    }
    /**
     * delete picture entry from database and reorder the pictures rank structure
     *
     * @param array $data
     */  
    private function delete($data)
    {
        $sql = "
            DELETE FROM
                {$this->config['dbTablePrefix']}navigation_media_pic
            WHERE
                id_pic='{$data['id_pic']}'";
        
        $this->model->dba->query($sql);    
        
        // Reorder the picture rank
        $sql = "
            SELECT `id_pic` FROM
                {$this->config['dbTablePrefix']}navigation_media_pic
            WHERE
                id_node='{$data['id_node']}'
            ORDER BY `rank` ASC";
        
        $stmt = $this->model->dba->query($sql);   
        $rank = 1;
        
        while($row = $stmt->fetchAssoc())
        {
            $sql = "UPDATE {$this->config['dbTablePrefix']}navigation_media_pic
                      SET
                        `rank`={$rank}
                    WHERE
                      `id_pic`={$row['id_pic']}";

            $this->model->dba->query($sql);   
            $rank++;
        }
    }
    
    private function update(&$data)
    {
        $comma = '';
        $fields = '';
        
        foreach($data['fields'] as $key => $val)
        {
            $fields .= $comma.'`'.$key.'`=?';
            $comma = ',';
        }
        
        $sql = "UPDATE {$this->config['dbTablePrefix']}navigation_node
                  SET
                   $fields
                  WHERE
                   `id_node`={$data['id_node']}";

        $stmt = $this->model->dba->prepare($sql);                    
        
        foreach($data['fields'] as $key => $val)
        {
            $methode = 'set'.$this->tblFields_node[$key];
            $stmt->$methode($val);
        }
       
        $stmt->execute();  
    }    
}

?>
