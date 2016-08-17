<?php


namespace Laracore\Exception;


class NoCriteriaClosureSetException extends \Exception
{

    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        if (!isset($message)) {
            $message = 'No closure set on Criteria. Please set a closure.';
        }

        parent::__construct($message, $code, $previous);
    }

}