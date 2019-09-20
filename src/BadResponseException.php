<?php
namespace p4it\rest\client;

use yii\httpclient\Exception;
use yii\httpclient\Response;


class BadResponseException extends Exception
{
    /**
     * @var Response|null
     */
    public $response;

    /**
     * Constructor.
     * @param Response|null $response
     * @param string $message error message
     * @param int $code error code
     * @param \Exception $previous The previous exception used for the exception chaining.
     */
    public function __construct(?Response $response = null, $message = null, $code = 0, \Exception $previous = null)
    {
        $this->response = $response;
        parent::__construct($message, $code, $previous);
    }
}
