<?php

class SmartViewException extends SmartException
{
    public function __construct ($message = null, $code = 0)
    {
        parent::__construct($message, $code);

        $this->setName( 'SmartViewException' );
    }
}

?>
