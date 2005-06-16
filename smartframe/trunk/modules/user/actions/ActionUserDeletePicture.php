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

class ActionUserDeletePicture extends SmartAction
{
    /**
     * Delete user picture
     *
     * @param array $data
     * @return bool
     */
    public function perform( $data = FALSE )
    {
        $pic = array();

        $this->model->action('user',
                             'getImage',
                             array('result' => & $pic,
                                   'id_pic' => $data['id_pic'],
                                   'fields' => array('file','id_user')));   

        $user = array();

        $this->model->action('user',
                             'getUser',
                             array('result'  => & $user,
                                   'id_user' => $pic['id_user'],
                                   'fields'  => array('media_folder')));   

        if(!@unlink(SMART_BASE_DIR . 'data/user/'.$user['media_folder'].'/'.$pic['file']))
        {
           trigger_error('Cant delete user logo: data/user/'.$user['media_folder'].'/'.$_file['logo'], E_USER_WARNING);
        }
        if(!@unlink(SMART_BASE_DIR . 'data/user/'.$user['media_folder'].'/thumb/'.$pic['file']))
        {
           trigger_error('Cant delete user logo: data/user/'.$user['media_folder'].'/thumb/'.$_file['logo'], E_USER_WARNING);
        }    
        
        $this->model->action('user',
                             'updatePicture',
                             array('action'  => 'delete',
                                   'id_pic'  => $data['id_pic'],
                                   'id_user' => $pic['id_user']));
        
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
        if(!isset($data['id_pic']))
        {
            throw new SmartModelException("No 'id_pic' defined");
        }

        if(preg_match("/[^0-9]/",$data['id_pic']))
        {
            throw new SmartModelException("'id_pic' isnt numeric");
        }
        
        return TRUE;
    } 
}

?>