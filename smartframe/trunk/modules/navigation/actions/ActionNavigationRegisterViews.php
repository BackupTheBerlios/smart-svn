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
 * ActionNavigationGetNodePublicViews
 *
 */
 
class ActionNavigationRegisterViews extends SmartAction
{
    /**
     * get data of all users
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {
        if($data['action'] == 'register')
        {
            $sql = "
                SELECT `id_view` FROM {$this->config['dbTablePrefix']}navigation_view
                 WHERE `name`='{$data['name']}'";

            $rs = $this->model->dba->query($sql);    
      
            if($rs->numRows() == 0)
            {    
                $sql = "
                    INSERT INTO {$this->config['dbTablePrefix']}navigation_view
                     (`name`)
                    VALUES
                     ('{$data['name']}')";

                $rs = $this->model->dba->query($sql);
            }
        }
        elseif($data['action'] == 'unregister')
        {
            $sql = "
                DELETE FROM {$this->config['dbTablePrefix']}navigation_view
                WHERE
                   `id_view`={$data['id_view']}";

            $rs = $this->model->dba->query($sql);
        }        
        return TRUE;
    } 
    
    public function validate( $data = FALSE )
    {


        return TRUE;
    }
}

?>
