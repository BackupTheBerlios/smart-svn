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
 * earchive_fetch_emails class 
 *
 */

// the PEAR IMAP class
include_once (SF_BASE_DIR . 'modules/common/PEAR/MAIL/mimeDecode.php');
 
class action_earchive_fetch_emails extends action
{
    /**
     * fetch emails from accounts
     *
     * @param array $data
     */
    function perform( $data )
    {    
        // fetch list with specific status
        if( isset($data['status']) )
        {
            $status = $data['status'];
        }
        else
        {
            $status = NULL;
        }

        // get all available lists
        M( MOD_EARCHIVE, 
           'get_lists', 
           array( 'var'    => 'all_lists',
                  'status' => $status,
                  'fields' => array('lid','emailserver','folder')));        

        if(count($this->B->all_lists) > 0)
        {            

                $mbox = imap_open ("{pop3.a-tu.net/pop3:110}INBOX", "903522", "test3");
$msg = new MsgR($mbox,2);
                echo '<pre>';print_r($msg->header); echo '</pre><br><br><br>';
                
  print "<br><br>Content:<br>";
while( list($num,$part) = each($msg->parts)){
    print "<br>".$num." -> ".$part->subtype."<br><br>";
    print "<br><br>";
    #you can now easily open the part trough imap_fetchbody();
    #the first element in $msg->parts should be the plain text :)
}                 
           
        }
    }

}
    


class MsgR{
    var $mbox; #mailbox-handle
    
    #things to collect:
    var $body; #the raw header
    var $parts = array();    
    var $header = array();




    function MsgR($mbox, $id){
    $this->mbox = $mbox;
    $this->id = $id;
    
    $this->read_content();
    $this->read_header();
    }
            
    
    function read_header(){
    $header = imap_headerinfo($this->mbox, $this->id, 80, 80);
    $this->check_errors(1);
    #From:
    $this->header["from"] = $header->from[0]->mailbox."@".$header->from[0]->host;
    #Subject decode::
    $decode =  imap_mime_header_decode($header->fetchsubject);
    $this->header["subject"] = "";
    for ($c=0; $c<count($decode); $c++) {
        $this->header["subject"] .= $decode[$c]->text;
    }
    if ($this->header["subject"] == ""){
        $this->header["subject"] = "No Subject";
    }
    #Date hack:
    if (eregi ("([0-9]{1,2} [a-z]+ [0-9]{4}) ([0-9:]+)",$header->date,$regs)){
        $this->header["date"] = $regs[1]." ".$regs[2];
    }
    else{
        $this->header["date"] = $header->date;
    }        
    }
    

    
    function read_content(){    
    $this->body = imap_fetchbody($this->mbox,$this->id,0);
    $structure = imap_fetchstructure($this->mbox,$this->id);
    $this->check_errors(1);
    if ($structure->type==0){ #Just a Plain Mail
        $this->parts["1"] = $structure;
    }
    else{ #Multipart
        $col = new colParts();
        $col->collect($structure);
        $this->parts = $col->parts;
    }
    }
    
    
    #Error Handling:
    function check_errors($critical=0){
    if ($err = imap_errors()){
        print "<br><pre>";
        print_r($err);
        print "</pre><br>";
        if ($critical){
        exit();
        }
    }
    return;
    }

    
}
    #
    ##
    # Author: Moritz Thielcke (www.mthielcke.de)
    # Date: 26.01.05
    #
    ##
    # Saves the single-parts of the email to the following structur: "path => subtype"
    # for example: "1.1.1 => OBJECT
    #               1.1.2 => OBJECT

    class colParts{
    var $parts = array(); #collected parts


    
    function colParts(){
    }


    #Increases the Path to the parts:
    function incPath($path, $inc){
        $newpath="";
        $path_elements = explode(".",$path);
        $limit = count($path_elements);
        for($i=0;$i<$limit;$i++){
        if($i == $limit-1){ #last element
            $newpath .= $path_elements[$i]+$inc; # new Part-Number
        }
        else{
            $newpath .= $path_elements[$i]."."; #rebuild "1.2.2"-Chronology
        }
        }
        return $newpath;
    }
    
    
    #Collect the Single-Parts:
    function collect($struct, $path="0",$inline=0){    
        $parts = $struct->parts;
        $c=0; #counts real content
        foreach ($parts as $part){
        if ($part->type==1){ 
            #There are more parts....:
            if ($part->subtype=="MIXED"){ #Mixed:
            $path = $this->incPath($path,1); #refreshing current path
            $newpath = $path.".0"; #create a new path-id (ex.:2.0)
            $this->collect($part,$newpath); #fetch new parts            
            }
            else{ #Alternativ / rfc / signed
            $newpath = $this->incPath($path, 1);
            $path = $this->incPath($path,1);
            $this->collect($part,$newpath,1);            
            }
        }
        else {
            $c++;
            #creating new tree if this is part of a alternativ or rfc message:
            if ($c==1 && $inline){  
            $path = $path.".0";
            }
            #saving content:
            $path = $this->incPath($path, 1);
            #print "<br>  Content ".$path."<br>";        #debug information
            $this->parts[$path] = $part;
        }
        
        }        
        
    }


    
    }
?>
