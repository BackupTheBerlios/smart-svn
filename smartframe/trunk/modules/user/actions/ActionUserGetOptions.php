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
 * ActionUserGetOptions class 
 *
 *
 * USAGE:
 *
 * $model->action('user','getOptions',
 *                array('result' => & array()))
 *
 */
 
class ActionUserGetOptions extends SmartAction
{
    /**
     * get all user module options
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {
        $sql = "
            SELECT
                *
            FROM
                {$this->config['dbTablePrefix']}user_config";

        $rs = $this->model->dba->query($sql);
        
        $data['result'] = $rs->fetchAssoc();
        
        return TRUE;
    } 
    
    public function validate( $data = FALSE )
    {
        if(!isset($data['result']))
        {
            throw new SmartModelException("No 'result' defined");
        }
        if(!is_array($data['result']))
        {
            throw new SmartModelException("'result' isnt from type array");
        }
        return TRUE;
    }
}

?>
