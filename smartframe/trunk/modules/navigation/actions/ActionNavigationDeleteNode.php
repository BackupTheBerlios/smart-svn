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
 * ActionNavigationDeleteNode class 
 *
 */
 
class ActionNavigationDeleteNode extends SmartAction
{
    /**
     * delete navigation node and referenced table entries
     *
     * @param array $data
     * @return bool true or false on error
     */
    public function perform( $data = FALSE )
    {        
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}navigation_lock
                  WHERE
                   `id_node`={$data['id_node']}";

        $this->model->dba->query($sql);

        $sql = "DELETE FROM {$this->config['dbTablePrefix']}navigation_media_pic
                  WHERE
                   `id_node`={$data['id_node']}";

        $this->model->dba->query($sql);

        $sql = "DELETE FROM {$this->config['dbTablePrefix']}navigation_media_file
                  WHERE
                   `id_node`={$data['id_node']}";

        $this->model->dba->query($sql);

        $sql = "SELECT `media_folder` FROM {$this->config['dbTablePrefix']}navigation_node
                  WHERE
                   `id_node`={$data['id_node']}";
                   
        $rs = $this->model->dba->query($sql);

        $row = $rs->fetchAssoc();

        if(isset($row['media_folder']) && !empty($row['media_folder']))
        {
            // delete user data media folder
            SmartCommonUtil::deleteDirTree( SMART_BASE_DIR.'data/navigation/'.$row['media_folder'] );
        }
        else
        {
            trigger_error("Navigation media folder string is empty! \nFILE: ".__FILE__, E_USER_WARNING);
        }
        
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}navigation_node
                  WHERE
                   `id_node`={$data['id_node']}";

        $this->model->dba->query($sql);
       
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
        if(preg_match("/[^0-9]/",$data['id_node']))
        {
            throw new SmartModelException('Wrong id_node format: '.$id_user);        
        }

        return TRUE;
    }
}

?>
