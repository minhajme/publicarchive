<?php

namespace Resgef\SyncList\Exceptions\EbayApiException;

use Throwable;

class EbayApiException extends \Exception
{
    public $callname;

    function __construct($callname, $message = "empty response", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->callname = $callname;
    }
}