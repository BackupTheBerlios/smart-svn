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
 * ActionNavigationUpdateItemDescriptions class 
 *
 */
class ActionNavigationUpdateItemDescriptions extends SmartAction
{
    /**
     * update user pictures/files descriptions
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    { 
        // set table name and item reference
        if(isset($data['fid']))
        {
            $this->table     = 'navigation_media_file';
            $this->tbl_field = 'id_file';
            $this->id_item   = 'fid';
        }
        else
        {
            $this->table     = 'navigation_media_pic';
            $this->tbl_field = 'id_pic';
            $this->id_item   = 'pid';
        }
        
        $sql = "UPDATE {$this->config['dbTablePrefix']}{$this->table}
                  SET `description`=?
                  WHERE
                   `id_node`={$data['id_node']}
                  AND
                   `{$this->tbl_field}`=?";

        $stmt = $this->model->dba->prepare($sql);                    
        
        $x=0;
        foreach($data['desc'] as $desc)
        {
            $stmt->setString(SmartCommonUtil::stripSlashes($desc));
            $stmt->setInt($data[$this->id_item][$x]);
            $stmt->execute();  
            $x++;
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
        if( !isset($data['id_node']) )
        {        
            throw new SmartModelException ('"id_node" must be defined'); 
        } 
        elseif(@preg_match("/[^0-9]/", $data['id_node'])  )
        {        
            throw new SmartModelException ('"id_node" must be an integer'); 
        } 
        
        if( !isset($data['pid']) && !isset($data['fid']) )
        {        
            throw new SmartModelException ('"fid" or "pid" must be defined'); 
        }
        
        if( isset($data['fid']) && !is_array($data['fid'])  )
        {        
            throw new SmartModelException ('"fid" must be an array'); 
        }  
        
        if( isset($data['pid']) && !is_array($data['pid'])  )
        {        
            throw new SmartModelException ('"pid" must be an array'); 
        }  
        
        return TRUE;
    }
}

?>
