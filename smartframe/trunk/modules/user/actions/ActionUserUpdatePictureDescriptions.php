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
 * ActionUpdatePicture class 
 *
 */
class ActionUserUpdatePictureDescriptions extends SmartAction
{
    /**
     * update/delete user picture from db table
     *
     * @param array $data
     * @return int user id or false on error
     */
    function perform( $data = FALSE )
    { 
        
        $sql = "UPDATE {$this->config['dbTablePrefix']}user_media_pic
                  SET `description`=?
                  WHERE
                   `id_user`={$data['id_user']}
                  AND
                   `id_pic`=?";

        $stmt = $this->model->dba->prepare($sql);                    
        
        $x=0;
        foreach($data['desc'] as $desc)
        {
            $stmt->setString(SmartCommonUtil::stripSlashes($desc));
            $stmt->setInt($data['pid'][$x]);
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
        if(!isset($data['id_user']))
        {
            throw new SmartModelException("No 'id_user' defined. Required!");
        }
        if(!isset($data['pid']))
        {
            throw new SmartModelException("No 'pid' defined. Required!");
        }
        elseif(!is_array($data['pid']))
        {
            throw new SmartModelException("Param 'pid' must be an array. Required!");
        } 
        
        if(!isset($data['desc']))
        {
            throw new SmartModelException("No 'desc' defined. Required!");
        } 
        elseif(!is_array($data['desc']))
        {
            throw new SmartModelException("Param 'desc' must be an array. Required!");
        } 
        
        if(preg_match("/[^0-9]/",$data['id_user']))
        {
            throw new SmartModelException("'id_user' isnt numeric");
        }
        
        return TRUE;
    }
}

?>
