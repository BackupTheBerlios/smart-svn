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
 * ActionCommonGetAllPublicViews
 *
 * USAGE:
 * $model->action( 'common','getAllPublicViews',
 *                 array('result' => & array );   
 *
 */

class ActionCommonGetAllPublicViews extends SmartAction
{
    /**
     * Perform on the action call
     *
     * find all public view classe php files of the folder views_xxx/
     *
     * @param mixed $data
     */
    public function perform( $data = FALSE )
    {
        $view_name = array();
        $view_dir = SMART_BASE_DIR . $this->model->config['views_folder'];
          
        if ( (($handle = @opendir( $view_dir ))) != FALSE )
        {
            while ( (( $file = readdir( $handle ) )) != false )
            {
                if ( ( $file == "." ) || ( $file == ".." ) )
                {
                    continue;
                }
                if(preg_match("/^View([a-zA-z0-9_]+)\.php$/", $file, $name))
                {
                    $view_name[] = $name[1];
                }
            }
            @closedir( $handle );
        }
        else
        {
            trigger_error( "Can not open view folder to read: ".$view_dir, E_USER_ERROR  );
        }
        
        sort( $view_name );
        
        $tpl_dir = SMART_BASE_DIR . $this->model->config['templates_folder'];
        
        $data['result'] = array();
        
        foreach($view_name as $name)
        {
            if(file_exists($tpl_dir . '/tpl.' . $name . '.php'))
            {
                $data['result'][] = array('name' => $name,
                                          'tpl'  => TRUE);
            }
            else
            {
                $data['result'][] = array('name' => $name,
                                          'tpl'  => FALSE);            
            }
        }   
    }
    
    /**
     * Validate data passed to this action
     */
    public function validate( $data = FALSE )
    {
        // The Exception catch has to react
        //
        if( !isset($data['result']) )
        {
            throw new SmartModelException("No 'result' array var defined");
        }
        
        return TRUE;
    }    
}

?>