<?php
// ----------------------------------------------------------------------
// Smart PHP Framework
// Copyright (c) 2004, 2005
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/*
 * Mysqli classes
 *
 */


class DbMysqli
{    
    protected $user;
    protected $pass;
    protected $dbhost;
    protected $dbname;
    protected $dbport;
    protected $dbsocket;
    protected $dbflags;
    private   $dbh = NULL;

    public function __construct($dbhost, $user, $pass, $dbname, $dbport = FALSE, $dbsocket = FALSE, $dbflags = FALSE) 
    {
        $this->user     = $user;
        $this->pass     = $pass;
        $this->dbhost   = $dbhost;
        $this->dbname   = $dbname;
        $this->dbport   = $dbport;
        $this->dbsocket = $dbsocket;
        $this->dbflags  = $dbflags;
        
        $this->dbh = mysqli_init();
    }
  
    public function connect( $options = FALSE ) 
    {
        if(($options != FALSE) && is_array($options))
        {
            foreach($options as $key => $val)
            {
                if(FALSE == $this->dbh->options($key, $val))
                {
                    throw new SmartDbException($this->dbh->error);
                }
            }
        }
        
        $connect = $this->dbh->real_connect($this->dbhost, $this->user, $this->pass, $this->dbname, $this->dbport, $this->dbsocket, $this->dbflags);
    
        if(FALSE == $connect) 
        {
            throw new SmartDbException($this->dbh->error);
        }
    }

    public function query( $query, $mode = MYSQLI_STORE_RESULT ) 
    {
        $result = $this->dbh->query($query, $mode); 

        if(FALSE === $result) 
        {
            throw new SmartDbException($this->dbh->error);
        }
        elseif(TRUE !== $result) 
        {
            $stmt = new DbMysqliStatement;
            $stmt->result = $result;
            return $stmt;
        }
    }
    
    public function prepare($query) 
    {
        $result = $this->dbh->prepare( $query ); 

        if(!$result) 
        {
            throw new SmartDbException($this->dbh->error);
        }
        
        $stmt = new DbMysqliBindStatement;  
        $stmt->result = & $result;
        return $stmt;
    }   
    
    public function escape( $str )
    {
        return $this->dbh->real_escape_string( $str );
    }
    
    public function close()
    {
        return $this->dbh->close();
    }   

    public function serverVersion()
    {
        return $this->dbh->server_info;
    }   
    
    public function queryInfo()
    {
        return $this->dbh->info;
    }  
    
    public function setCharset( $charset )
    {
        return $this->dbh->set_charset($charset);
    }     
}

class DbMysqliStatement
{
    public $result;
        
    public function fetchRow() 
    {
        return $this->result->fetch_row( $this->result );
    }
    
    public function fetchAssoc() 
    {
        return $this->result->fetch_assoc();
    }

    public function fetchObject() 
    {
        return $this->result->fetch_object( $this->result );
    }

    public function fetchArray( $type = MYSQLI_ASSOC ) 
    {
        return $this->result->fetch_array( $this->result, $type );
    }

    public function numRows() 
    {
        return $this->result->num_rows;
    }
    
    public function affectedRows() 
    {
        return $this->result->affected_rows;
    }     
   
    public function fetchAll( $type = 'fetchAssoc' ) 
    {
        $retval = array();
        while($row = $this->$type()) 
        {
            $retval[] = $row;
        }
        return $retval;
    }
    
    public function close()
    {
        return $this->result->close();
    }    
    
    public function free()
    {
        return $this->result->free();
    }       
}

class DbMysqliBindStatement extends DbMysqliStatement
{
    private $bindParamArray   = array();
    private $bindParamCounter = 0;        

    public function __construct()
    {
            $this->bindReset();
    }
 
    private function bindParam() 
    {
        call_user_func_array(array(& $this->result ,'bind_param'), $this->bindParamArray);
    }  
    
    public function bindResult( $arg = array() ) 
    {
        call_user_func_array(array(& $this->result ,'bind_result'), $arg);
    } 

    public function setInt( $val )
    {
        $this->bindParamArray[0] .= 'i';       
        $this->bindParamArray[$this->bindParamCounter++] = $val;        
    }

    public function setString( $val )
    {
        $this->bindParamArray[0] .= 's';       
        $this->bindParamArray[$this->bindParamCounter++] = $val;        
    }
    
    public function setBlob( $val )
    {
        $this->bindParamArray[0] .= 'b';       
        $this->bindParamArray[$this->bindParamCounter++] = $val;        
    }  
    
    public function setDouble( $val )
    {
        $this->bindParamArray[0] .= 'd';       
        $this->bindParamArray[$this->bindParamCounter++] = $val;        
    }    

    private function bindReset()
    {
        // init bind variables
        $this->bindParamArray    = array();
        $this->bindParamArray[0] = '';
        $this->bindParamCounter  = 1;    
    }
    
    public function execute() 
    {
        if($this->bindParamCounter > 0)
        {
            $this->bindParam();
            $this->bindReset();
        }
        
        if( FALSE ==  $this->result->execute() )
        {
            throw new SmartDbException($this->dbh->error);
        }
    } 
    
    public function fetch() 
    {
        return $this->result->fetch();
    }   
}

class DbMysqliResult 
{
    protected $stmt;
    protected $result = array();
    private $rowIndex = 0;
    private $currIndex = 0;
    private $done = false;
    private $type;
 
    public function __construct(DbMysqliStatement $stmt, $type = 'fetchAssoc' ) 
    {
        $this->stmt = $stmt;
        $this->type = $type;
    } 
  
    public function first() 
    {
        if(!$this->result) 
        {
            $this->result[$this->rowIndex++] = $this->stmt->$this->type();
        }
        $this->currIndex = 0;
        return $this;
    }
    
    public function last()
    {
        if(!$this->done) 
        {
            array_push($this->result, $this->stmt->fetchAll( $this->type ));
        }
        $this->done = true;
        $this->currIndex = $this->rowIndex = count($this->result) - 1;
        return $this;
    }
    
    public function next()
    {
        if($this->done) 
        {
            return false;
        }
        $offset = $this->currIndex + 1;
        if(!$this->result[$offset]) 
        {
            $row = $this->stmt->$this->type();
            if(!$row) 
            {
              $this->done = true;
              return false;
            }
            $this->result[$offset] = $row;
            ++$this->rowIndex;
            ++$this->currIndex;
            return $this;
        }
        else 
        {
            ++$this->currIndex;
            return $this;
        }
    }
    
    public function prev()
    {
        if($this->currIndex == 0) 
        {
          return false;
        }
        --$this->currIndex;
        return $this;
    }
    
    public function __get($value) 
    {
        if(array_key_exists($value, $this->result[$this->currIndex])) 
        {
          return $this->result[$this->currIndex][$value];
        }
    }
} 

?>