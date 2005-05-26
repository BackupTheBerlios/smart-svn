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
 * ActionUserDelete class 
 *
 */
 
class ActionUserDelete extends SmartAction
{
    /**
     * delete user
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    { 
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}user_access
                  WHERE
                   `id_user`={$data['id_user']}";

        $stmt = $this->model->db->executeUpdate($sql);

        $sql = "DELETE FROM {$this->config['dbTablePrefix']}user_lock
                  WHERE
                   `id_user`={$data['id_user']}";

        $stmt = $this->model->db->executeUpdate($sql);

        $sql = "DELETE FROM {$this->config['dbTablePrefix']}user_media_pic
                  WHERE
                   `id_user`={$data['id_user']}";

        $stmt = $this->model->db->executeUpdate($sql);

        $sql = "DELETE FROM {$this->config['dbTablePrefix']}user_media_file
                  WHERE
                   `id_user`={$data['id_user']}";

        $stmt = $this->model->db->executeUpdate($sql);

        $sql = "SELECT `media_folder` FROM {$this->config['dbTablePrefix']}user_user
                  WHERE
                   `id_user`={$data['id_user']}";
                   
        $rs = $this->model->db->executeQuery($sql, ResultSet::FETCHMODE_ASSOC);
        $rs->first();
        $row = array();
        $row = $rs->getRow();

        // delete user data media folder
        SmartCommonUtil::deleteDirTree( SMART_BASE_DIR.'data/user/'.$row['media_folder'] );
        
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}user_user
                  WHERE
                   `id_user`={$data['id_user']}";

        $stmt = $this->model->db->executeUpdate($sql);
       
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
        // Check if id_user exists
        if(preg_match("/[^0-9]/", $data['id_user']))
        {
            throw new SmartModelException('var id_user has wrong format');
        }  
        
        return TRUE;
    }
}

?>
