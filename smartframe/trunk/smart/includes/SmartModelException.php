<?php

class SmartModelException extends SmartException
{
    public function __construct ($message = null, $code = 0)
    {
        parent::__construct($message, $code);

        $this->setName( 'SmartModelException' );
    }

}

?>
