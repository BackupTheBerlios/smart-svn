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
 * NAVIGATION_SYS_LOAD_MODULE class 
 *
 */
 
class NAVIGATION_SYS_LOAD_MODULE
{
    /**
     * Global system instance
     * @var object $B
     */
    var $B;
    
    /**
     * constructor
     *
     */
    function NAVIGATION_SYS_LOAD_MODULE()
    {
        $this->__construct();
    }

    /**
     * constructor php5
     *
     */
    function __construct()
    {
        $this->B = & $GLOBALS['B'];
    }
    
    /**
     * Perform on admin requests for this module
     *
     * @param array $data
     */
    function perform( $data )
    {    
        // User rights class
        //include(SF_BASE_DIR.'/admin/modules/navigation/class.rights.php');  
        
        require_once(SF_BASE_DIR.'/admin/modules/common/PEAR/DB/NestedSet.php');

        $params = array(
                      'node_id'     => 'id',
                      'parent_id'   => 'rootid',
                      'left_id'     => 'l',
                      'right_id'    => 'r',
                      'order_num'   => 'norder',
                      'level'       => 'level',
                      'name'        => 'name',
                      'node_status' => 'status'
                      );

        $dsn = $this->B->sys['db']['dbtype'].'://'.$this->B->sys['db']['user'].':'.$this->B->sys['db']['passwd'].'@'.$this->B->sys['db']['host'].'/'.$this->B->sys['db']['name'];

        $nestedSet =& DB_NestedSet::factory('DB', $dsn, $params); 
        
        $nestedSet->node_table     = "{$this->B->sys['db']['table_prefix']}navigation_nested_set";
        $nestedSet->lock_table     = "{$this->B->sys['db']['table_prefix']}navigation_nested_set_locks";
        $nestedSet->sequence_table = "{$this->B->sys['db']['table_prefix']}navigation_seq_nested_set";
        
        
        // set the base template for this module
        $this->B->module = SF_BASE_DIR . '/admin/modules/navigation/templates/index.tpl.php'; 
 
        // Switch to module features
        switch($_REQUEST['mf'])
        {
            case 'edit_node':
                include( SF_BASE_DIR."/admin/modules/navigation/edit_node.php" ); 
                // set the base template for this module feature
                $this->B->section = SF_BASE_DIR . '/admin/modules/navigation/templates/edit_node.tpl.php';    
                break;
            case 'add_node':
                // have rights to add nav_node?
                if(FALSE == rights::ask_access_to_add_nav_node ())
                {
                    @header('Location: '.SF_BASE_LOCATION.'/admin/index.php?m=NAVIGATION');
                    exit;
                }    
        
                if(isset($_POST['addnavnode']))
                {
                    include( SF_BASE_DIR."/admin/modules/navigation/add_node.php" ); 
                }
                // set the base template for this module feature
                $this->B->section = SF_BASE_DIR . '/admin/modules/navigation/templates/add_node.tpl.php';
                break;    
            default:
                // set the default template for this module
                $this->B->section = SF_BASE_DIR . '/admin/modules/navigation/templates/default.tpl.php';    
                
                // check if we are at root level
                if(empty($_REQUEST['node_id']))
                {
                    // get root nodes
                    $this->B->nodes = $nestedSet->getRootNodes();
                }
                else
                {
                    // get child nodes
                    $this->B->nodes = $nestedSet->getChildren( (int)$_REQUEST['node_id'] );
                }
        }
       
    } 
}

?>
