<?php

namespace tests\resources;

use p4it\rest\client\ActiveResourceQuery;
use p4it\rest\client\BadResponseException;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception;

/**
 */

class RoomQuery extends ActiveResourceQuery {

    /**
     * @param null $db
     * @return Room|bool
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function one($db = null)
    {
        return parent::one($db); // TODO: Change the autogenerated stub
    }

    /**
     * @param null $db
     * @return Room[]
     * @throws BadResponseException
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function all($db = null)
    {
        return parent::all($db); // TODO: Change the autogenerated stub
    }
}