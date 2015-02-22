<?php

namespace RoughDate\Exception;

class UnrecognizedDateFormatException extends \Exception
{
    public function __construct($format)
    {
        parent::__construct("Failed to recognize date format of `{$format}`.", 500);
    }
}
