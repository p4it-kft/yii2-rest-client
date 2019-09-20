<?php
namespace p4it\rest\client;

use GuzzleHttp\ClientInterface;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\httpclient\Client;

/**
 * User: papppeter
 * Date: 13/09/2019
 * Time: 15:36
 *
 * ezek a sima expand-ok nem aktívok, nem lehet külön hívni őket.
 */

abstract class Resource extends Model {

    /**
     * Declares the name of the resource.
     * By default this method returns the class name as the resource name
     * You may override this method
     * if the resource name is not named after this convention.
     * @return string the resource name
     */
    public static function resourceName()
    {
        return Inflector::pluralize(Inflector::camel2id(StringHelper::basename(get_called_class())));
    }

    /**
     * @return ActiveResourceQuery
     */
    public static function find() {
        return new ActiveResourceQuery(static::class);
    }



    /**
     * {@inheritdoc}
     * @return static|null ActiveResource instance matching the condition, or `null` if nothing matches.
     */
    public static function findOne($condition)
    {
        return static::findByCondition($condition)->one();
    }

    /**
     * {@inheritdoc}
     * @return static[] an array of ActiveResource instances, or an empty array if nothing matches.
     */
    public static function findAll($condition)
    {
        return static::findByCondition($condition)->all();
    }

    /**
     * @return Client
     * @throws InvalidConfigException
     */
    public static function getClient() {
        /** @var Client $client */
        $client = Yii::$app->get('restClient');

        return $client;
    }

    /**
     * Updates records using the provided attribute values and conditions.
     *
     * For example, to change the status to be 1 for all customers whose status is 2:
     *
     * ```php
     * Customer::updateAll(['status' => 1], ['status' => '2']);
     * ```
     *
     * @param array $attributes attribute values (name-value pairs) to be saved for the record.
     * Unlike [[update()]] these are not going to be validated.
     * @param array $condition the condition that matches the records that should get updated.
     * Please refer to [[QueryInterface::where()]] on how to specify this parameter.
     * An empty condition will match all records.
     * @return int the number of rows updated
     */
    public static function updateAll($attributes, $condition = null)
    {
        // TODO: Implement updateAll() method.
    }

    /**
     * Deletes records using the provided conditions.
     * WARNING: If you do not specify any condition, this method will delete ALL rows in the table.
     *
     * For example, to delete all customers whose status is 3:
     *
     * ```php
     * Customer::deleteAll([status = 3]);
     * ```
     *
     * @param array $condition the condition that matches the records that should get deleted.
     * Please refer to [[QueryInterface::where()]] on how to specify this parameter.
     * An empty condition will match all records.
     * @return int the number of rows deleted
     */
    public static function deleteAll($condition = null)
    {
        // TODO: Implement deleteAll() method.
    }

    /**
     * Inserts the record into the database using the attribute values of this record.
     *
     * Usage example:
     *
     * ```php
     * $customer = new Customer;
     * $customer->name = $name;
     * $customer->email = $email;
     * $customer->insert();
     * ```
     *
     * @param bool $runValidation whether to perform validation (calling [[\yii\base\Model::validate()|validate()]])
     * before saving the record. Defaults to `true`. If the validation fails, the record
     * will not be saved to the database and this method will return `false`.
     * @param array $attributes list of attributes that need to be saved. Defaults to `null`,
     * meaning all attributes that are loaded from DB will be saved.
     * @return bool whether the attributes are valid and the record is inserted successfully.
     */
    public function insert($runValidation = true, $attributes = null)
    {
        // TODO: Implement insert() method.
    }
}