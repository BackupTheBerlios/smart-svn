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
 * ActionUserDeleteLogo class 
 *
 */

class ActionUserDeleteFile extends SmartAction
{
    /**
     * Delete user picture
     *
     * @param array $data
     * @return bool
     */
    public function perform( $data = FALSE )
    {
        $file = array();

        $this->model->action('user',
                             'getFile',
                             array('result' => & $file,
                                   'id_file' => $data['id_file'],
                                   'fields'  => array('file','id_user')));   

        $user = array();

        $this->model->action('user',
                             'getUser',
                             array('result'  => & $user,
                                   'id_user' => $file['id_user'],
                                   'fields'  => array('media_folder')));   

        if(!@unlink(SMART_BASE_DIR . 'data/user/'.$user['media_folder'].'/'.$file['file']))
        {
           trigger_error('Cant delete user logo: data/user/'.$user['media_folder'].'/'.$file['file'], E_USER_WARNING);
        }   
        
        $this->model->action('user',
                             'updateFile',
                             array('action'  => 'delete',
                                   'id_file' => $data['id_file'],
                                   'id_user' => $file['id_user']));
        
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
        if(!isset($data['id_file']))
        {
            throw new SmartModelException("No 'id_file' defined");
        }

        if(preg_match("/[^0-9]/",$data['id_file']))
        {
            throw new SmartModelException("'id_file' isnt numeric");
        }
        
        return TRUE;
    } 
}

?>