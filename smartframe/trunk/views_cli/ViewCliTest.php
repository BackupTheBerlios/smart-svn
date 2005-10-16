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
 * ViewCliTest class (default cli view)
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
        $message .= "\nThis is the Smart3 CLI default view 'cliTest'.";
        $message .= "\nThis view does nothing else than printing";
        $message .= "\nout the command line arguments\n";
        $message .= "#########################################################\n";

        $message .= var_export($this->argv, true)."\n";
        
        print $message;
    }
}

?>