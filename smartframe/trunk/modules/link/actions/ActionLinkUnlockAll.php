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
 * ActionLinkUnlockAll class 
 *
 */

/**
 * USAGE:
 *
 *
 * $model->action( 'link', 'unlockAll' );
 *
 */
class ActionLinkUnlockAll extends SmartAction
{
    /**
     * unlock all articles
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    { 
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}link_lock";

        $this->model->dba->query($sql);        
    }
}

?>