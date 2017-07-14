<?php

namespace Noox\Exceptions;

class NewsAnalyzerException extends \Exception
{
    public static function analyzerUrlNotSet()
    {
        return new static('The url for news analyzer service is not set. Check the .env file.');
    }
}