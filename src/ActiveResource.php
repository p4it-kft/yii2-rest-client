<?php
namespace p4it\rest\client;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\httpclient\Client;

/**
 * User: papppeter
 * Date: 13/09/2019
 * Time: 15:36
 */

abstract class ActiveResource extends BaseActiveResource {

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
     * @throws InvalidConfigException
     */
    public static function findOne($condition)
    {
        return static::findByCondition($condition)->one();
    }

    /**
     * {@inheritdoc}
     * @return static[] an array of ActiveResource instances, or an empty array if nothing matches.
     * @throws InvalidConfigException
     */
    public static function findAll($condition)
    {
        return static::findByCondition($condition)->all();
    }

    /**
     * @return ActiveHttpClient
     * @throws InvalidConfigException
     */
    public static function getClient() {
        /** @var ActiveHttpClient $client */
        $client = Yii::$app->get('httpClient');

        return $client;
    }


    /**
     * Creates an [[ActiveQuery]] instance with a given SQL statement.
     *
     * Note that because the SQL statement is already specified, calling additional
     * query modification methods (such as `where()`, `order()`) on the created [[ActiveQuery]]
     * instance will have no effect. However, calling `with()`, `asArray()` or `indexBy()` is
     * still fine.
     *
     * Below is an example:
     *
     * ```php
     * $customers = Customer::findBySql('SELECT * FROM customer')->all();
     * ```
     *
     * @param string $sql the SQL statement to be executed
     * @param array $params parameters to be bound to the SQL statement during execution.
     * @return ActiveResourceQuery the newly created [[ActiveResourceQuery]] instance
     */
    public static function findByUrl($url)
    {
        $query = static::find();
        $query->url = $url;

        return $query;
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

    /**
     * @param array $attributes
     * @param array $condition
     * @return array|int
     * @throws BadResponseException
     * @throws InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public static function updateOne($attributes, array $condition)
    {
        $client = static::getClient();
        $request = $client->createRequest();
        $request->setMethod('PATCH');
        $request->setData($attributes);

        $query = static::find();
        $request->setUrl($client->buildUpdateOneUrl($query, $condition));
        $response = $request->send();
        if (!$response->isOk) {
            throw new BadResponseException($response, $response->getContent(), $response->getStatusCode());
        }
        /** @var ActiveResponse $data */
        $data = $response->getData();

        return $data->content;
    }
}