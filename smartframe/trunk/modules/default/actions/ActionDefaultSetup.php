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
                 (`name`, `alias`, `rank`, `version`, `visibility`, `perm`, `release`)
                VALUES
                 ('default','Main Page',1,'0.1',1,40,'DATE: 6.5.2005 AUTHOR: Armand Turpel <framework@smart3.org>')";
        $this->model->dba->query($sql);            

        return TRUE;
    } 
}

?>