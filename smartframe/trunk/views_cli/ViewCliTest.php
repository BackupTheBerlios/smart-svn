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
 * ViewCliTest class
 *
 */

class ViewCliTest extends SmartCliView
{  
    /**
     * Execute this view
     */
    public function perform()
    {
        $message  = "\n#########################################################";
        $message .= "\nThis is the Smart3 CLI view 'cliTest'.";
        $message .= "\nThis view print out the top level navigation nodes\n";
        $message .= "#########################################################\n\n";
        
        // print header message
        fwrite(STDOUT, $message, strlen($message));
        
        $this->tplVar['rootNodes'] = array(); 
        // get top level nodes that have id_node = 0 as id_parent
        $this->model->action( 'navigation', 'getChilds', 
                              array('id_node' => 0,
                                    'result'  => & $this->tplVar['rootNodes'],
                                    'status'  => array('=', 2),
                                    'fields'  => array('title','id_node'))); 

        // print top level nodes
        foreach($this->tplVar['rootNodes'] as $node)
        {
            $message = "NodeID: {$node['id_node']} \t Title: {$node['title']}\n";
            fwrite(STDOUT, $message, strlen($message));
        }
    }
}

?>