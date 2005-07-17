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
 * ActionNavigationDeleteLogo class 
 *
 */

class ActionNavigationDeleteLogo extends SmartAction
{
    /**
     * Delete node logo
     *
     * param:
     * data['id_node']
     *
     * @param array $data
     * @return bool
     */
    public function perform( $data = FALSE )
    {
        $_file = '';

        $this->model->action('navigation',
                             'getNode',
                             array('result'  => & $_file,
                                   'id_node' => $data['id_node'],
                                   'fields'  => array('logo','media_folder')));   

        if(!@unlink(SMART_BASE_DIR . 'data/navigation/'.$_file['media_folder'].'/'.$_file['logo']))
        {
            throw new SmartModelException('Cant delete user logo: data/navigation/'.$_file['media_folder'].'/'.$_file['logo']);
        }
                            
        $this->model->action('navigation',
                             'updateNode',
                             array('id_node' => $data['id_node'],
                                   'fields'  => array('logo' => '')));
        
        return TRUE;
    }
    
    /**
     * validate the parameters passed in the data array
     *
     * @param array $data
     * @return bool
     */    
    public function validate(  $data = FALSE  )
    {
        if(preg_match("/[^0-9]/",$data['id_node']))
        {
            throw new SmartModelException('Wrong id_node format: '.$id_node);        
        }
        
        return TRUE;
    }
}

?>