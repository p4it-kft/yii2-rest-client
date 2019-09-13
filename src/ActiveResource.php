<?php
namespace p4it\rest\client;

use GuzzleHttp\ClientInterface;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;

/**
 * User: papppeter
 * Date: 13/09/2019
 * Time: 15:36
 */

abstract class ActiveResource extends Model {

    /**
     * @return ActiveResourceQuery
     */
    public static function find() {
        return new ActiveResourceQuery(static::class);
    }

    /**
     * @return ClientInterface
     * @throws InvalidConfigException
     */
    public function getClient() {
        /** @var ClientInterface $client */
        $client = Yii::$app->get('restClient');

        return $client;
    }

}