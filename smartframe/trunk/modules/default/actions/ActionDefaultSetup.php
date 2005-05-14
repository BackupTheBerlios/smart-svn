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
 * Setup action of the common module 
 *
 */
 
class ActionDefaultSetup extends SmartAction
{
    /**
     * Run setup process of this module
     *
     */
    public function perform( $data = FALSE )
    {
        $sql = "INSERT INTO {$data['config']['db']['dbTablePrefix']}common_module
                 (`name`, `rank`, `version`, `visibility`, `release`)
                VALUES
                 ('default',1,'0.1',1,'DATE: 6.5.2005 AUTHOR: Armand Turpel <smart@open-publisher.net>')";
        $this->model->db->executeUpdate($sql);            

        return TRUE;
    } 
}

?>