<?php




// Check if this file is included in the environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on '. __FILE__);
}

// Init error var
$base->tmp_error_system = array();

// Do setup 
if( $_POST['do_setup'] )
{

    if( empty($_POST['host']) )
    {
        $base->tmp_error_system['host'] = 'Host field is empty!<br />';
    }
    if( empty($_POST['login']) )
    {
        $base->tmp_error_system['login'] = 'Login field is empty!<br />';
    }
    if( ($_POST['password1'] != $_POST['password2']) )
    {
        $base->tmp_error_system['pass'] = 'Password fields are empty or not equal!<br />';
    } 
    if( empty($_POST['db_name']) )
    {
        $base->tmp_error_system['db_name'] = 'DB name field is empty!<br />';
    }
  
    if( count($base->tmp_error_system) == 0 )
    {
        // set db resource
        $base->dsn = $_POST['db_type'].'://'.$_POST['login'].':'.$_POST['password'].'@'.$_POST['host'].'/'.$_POST['db_name'];

        // db connect
        $base->db = & DB::connect($base->dsn);
        if (DB::isError($base->db)) 
        {
            $base->tmp_error_system['db'] = $base->db->getMessage();
        }        

        if( count($base->tmp_error) == 0 )
        {
            $base->tmp_config['db.smart'] = array(
                                       'db_host'         => $_POST['host'],
                                       'db_user'         => $_POST['login'],
                                       'db_passwd'       => $_POST['password'],
                                       'db_type'         => $_POST['db_type'],
                                       'db_name'         => $_POST['db_name'],
                                       'db_table_prefix' => $_POST['table_prefix'] );
     
            $base->conf->setConfigValues( $base->tmp_config );
            $base->conf->writeConfigFile('config_db_connect.xml.php', 'xml', array('mode' => 'pretty'));
            
            $base->tmp_table_prefix = $_POST['table_prefix'];
        }
        else
        {
            // Assign module handler name
            //$base->tpl->addRows( 'setup', 'error', $base->tmp_error_system );
        }
    }
    else
    {
        // Assign module handler name
        //$base->tpl->addRows( 'setup', 'error', $base->tmp_error_system );    
    }
      //var_dump($base->tmp_error_system);  exit;
}

  


?>