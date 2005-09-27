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
 
class ActionOptionsSetup extends SmartAction
{
    /**
     * Run setup process of this module
     *
     */
    public function perform( $data = FALSE )
    {
        if(isset($data['rollback']))
        {
            $this->rollback($data);
            return TRUE;
        }

        $sql = "INSERT INTO {$data['config']['db']['dbTablePrefix']}common_module
                   (`name`, `alias`, `rank`, `version`, `visibility`, `release`)
                  VALUES
                   ('options',
                    'Global Options Management',
                    7,
                    '0.1',
                    1,
                    'DATE: 25.7.2005 AUTHOR: Armand Turpel <smart@open-publisher.net>')";
        $this->model->dba->query($sql);            
    } 

    /**
     * Rollback setup
     * Delete db tables of this module 
     *
     */    
    public function rollback( &$data )
    {
 
    }
}

?>