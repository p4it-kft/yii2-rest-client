<?php
namespace p4it\rest\client;

use http\Client\Request;
use yii\base\Event;

class AfterGetClientEvent extends Event
{
    /**
     * @var ActiveHttpClient
     */
    public $client;
}
