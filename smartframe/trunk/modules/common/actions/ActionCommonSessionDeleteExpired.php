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
 * ActionCommonSessionDeleteExpired
 *
 * USAGE:
 * $model->action( 'common', 'sessionDeleteExpired');
 *
 */

class ActionCommonSessionDeleteExpired extends SmartAction
{
    /**
     * Delete current expired session
     *
     * @param mixed $data
     */
    public function perform( $data = FALSE )
    {
        $ts = time() - $this->config['session_maxlifetime'];
        
        $result = $this->model->dba->query(
                         "SELECT 
                             `modtime` 
                          FROM 
                             {$this->config['dbTablePrefix']}common_session
                          WHERE 
                              `modtime`<{$ts}
                          AND
                              `session_id`='{$this->model->session->getId()}'");
        
        if($result->numRows() > 0)
        {
            $this->model->session->destroy();
        }
    }
}

?>