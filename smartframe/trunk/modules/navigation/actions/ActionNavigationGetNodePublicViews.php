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
 
class ActionNavigationGetNodePublicViews extends SmartAction
{
    private $tblFields_view = array('id_view'     => TRUE,
                                    'name'        => TRUE,
                                    'description' => TRUE);
    /**
     * get data of all users
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {
        $comma = '';
        $_fields = '';
        foreach ($data['fields'] as $f)
        {
            $_fields .= $comma.'`'.$f.'`';
            $comma = ',';
        }

        $sql = "
            SELECT
                {$_fields}
            FROM
                {$this->config['dbTablePrefix']}navigation_view
            ORDER BY `name`";

        $rs = $this->model->dba->query($sql);
        
        $data['result'] = array();
        
        while($row = $rs->fetchAssoc())
        {            
            $data['result'][] = $row;
        } 
        
        return TRUE;
    } 
    
    public function validate( $data = FALSE )
    {
        foreach($data['fields'] as $key)
        {
            if(!isset($this->tblFields_view[$key]))
            {
                throw new SmartModelException("Field '".$key."' dosent exists!");
            }
        }

        return TRUE;
    }
}

?>
