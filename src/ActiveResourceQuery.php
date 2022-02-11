<?php
namespace p4it\rest\client;

use Generator;
use InvalidArgumentException;
use ReflectionException;
use ReflectionMethod;
use yii\base\InvalidConfigException;
use yii\db\Connection;
use yii\helpers\VarDumper;
use yii\httpclient\Client;
use yii\httpclient\Exception;
use yii\httpclient\Request;

/**
 * User: papppeter
 * Date: 13/09/2019
 * Time: 15:41
 */

class ActiveResourceQuery extends ResourceQuery implements ActiveResourceQueryInterface {
    use ActiveResourceQueryTrait;
    use ActiveResourceRelationTrait;

    /**
     * @event Event an event that is triggered when the query is initialized via [[init()]].
     */
    const EVENT_INIT = 'init';

    /**
     * @var string the url statement to be executed for retrieving AR records.
     * This is set by [[ActiveRecord::findBySql()]].
     */
    public $url;

    /**
     * @var string
     *
     * https://www.yiiframework.com/doc/api/2.0/yii-data-datafilter
     */
    public $filterParam = 'filter';

    /**
     * we are using with
     *
     * @var string
     */
    public $expandParam = 'expand';

    /**
     * we are using select
     *
     * @var string
     */
    public $fieldParam = 'fields';

    /**
     * we are using orderby
     *
     * @var string
     */
    public $sortParam = 'sort';

    public $perPage = 100;
    public $perPageParam = 'per-page';

    /**
     * Constructor.
     * @param string $modelClass the model class associated with this query
     * @param array $config configurations to be applied to the newly created query object
     */
    public function __construct($modelClass, $config = [])
    {
        $this->modelClass = $modelClass;
        parent::__construct($config);
    }

    /**
     * Initializes the object.
     * This method is called at the end of the constructor. The default implementation will trigger
     * an [[EVENT_INIT]] event. If you override this method, make sure you call the parent implementation at the end
     * to ensure triggering of the event.
     */
    public function init()
    {
        parent::init();
        $this->trigger(self::EVENT_INIT);
    }

    /**
     * Starts a batch query.
     *
     * A batch query supports fetching data in batches, which can keep the memory usage under a limit.
     * This method will return a [[BatchQueryResult]] object which implements the [[\Iterator]] interface
     * and can be traversed to retrieve the data in batches.
     * @param int $batchSize
     * @param null $client
     * @return Generator
     * @throws BadResponseException
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function batch($batchSize = 100, $client = null): Generator
    {
        if ($this->emulateExecution) {
            $items = [[]];
            return yield $items;
        }

        $oldPerPage = $this->perPage;
        $this->perPage = $batchSize;

        $request = $this->createRequest($client);

        foreach ($this->getItems($request) as [$items]) {
            yield $this->populate($items);
        }

        $this->perPage = $oldPerPage;
    }

    /**
     * Starts a batch query and retrieves data row by row.
     *
     * This method is similar to [[batch()]] except that in each iteration of the result,
     * only one row of data is returned. For example,
     * @param int $batchSize
     * @param null $client
     * @return Generator
     * @throws BadResponseException
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function each($batchSize = 100, $client = null): Generator
    {
        foreach ($this->batch($batchSize, $client) as $items) {
            foreach ($items as $item) {
                yield $item;
            }
        }
    }

    /**
     * Executes the query and returns all results as an array.
     * @param Connection $client the database connection used to generate the SQL statement.
     * If this parameter is not given, the `db` application component will be used.
     * @return array the query results. If the query results in nothing, an empty array will be returned.
     * @throws BadResponseException
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function all($client = null)
    {
        if ($this->emulateExecution) {
            return [];
        }

        $items = [];

        foreach ($this->each($this->perPage, $client) as $item) {
            $items[] = $item;
        }

        return $items;
    }

    /**
     * Converts the raw query results into the format as specified by this query.
     * This method is internally used to convert the data fetched from database
     * into the format as required by this query.
     * @param array $rows the raw query result from database
     * @return array the converted query result
     */
    public function populate($rows)
    {
        if (empty($rows)) {
            return [];
        }

        if($this->isPopulated($rows)) {
            return $rows;
        }

        $models = $this->createModels($rows);

        if ($this->expand) {
            $primaryModel = reset($models);
            $expands = array_filter($this->expand, fn ($expand) => !$primaryModel->hasAttribute($expand));
            $this->findExpand($expands, $models, $rows);
        }

        if (!$this->asArray) {
            foreach ($models as $model) {
                /** @var ActiveResource $model */
                $model->afterFind();
            }
        }

        return parent::populate($models);
    }

    /**
     * Executes the query and returns a single row of result.
     * @param Client $client the http connection used to create the request.
     * If this parameter is not given, the `db` application component will be used.
     * @return array|bool the first row (in terms of an array) of the query result. False is returned if the query
     * results in nothing.
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function one($client = null)
    {
        if ($this->emulateExecution) {
            return null;
        }

        $items = [];

        foreach ($this->each(1, $client) as $item) {
            $items[] = $item;
            break;
        }
        
        if(!$items) {
            return null;
        }

        return reset($items);
    }


    /**
     * Prepares for building SQL.
     * This method is called by [[QueryBuilder]] when it starts to build SQL from a query object.
     * You may override this method to do some final preparation work when converting a query into a SQL statement.
     * @param ActiveResourceQuery $builder
     */
    public function prepare()
    {
        if ($this->primaryModel === null) {
            return;
        } else {
            // lazy loading of a relation
            $this->filterByModels([$this->primaryModel]);
        }
    }

    /**
     * Creates a DB command that can be used to execute this query.
     * @param Connection|null $db the DB connection used to create the DB command.
     * If `null`, the DB connection returned by [[modelClass]] will be used.
     * @return Request the created DB command instance.
     * @throws InvalidConfigException
     */
    public function createRequest($client = null)
    {
        /* @var $modelClass ActiveResource */
        $modelClass = $this->modelClass;
        if ($client === null) {
            $client = $modelClass::getClient();
        }

        if ($this->url === null) {
            //todo url builder
            $url = $client->buildUrl($this);
        } else {
            $url = $this->url;
        }

        $request = $client->createRequest();
        $request->setMethod('GET');
        $request->setUrl($url);

        return $request;
    }

    /*
     *
 * - `'PUT,PATCH users/<id>' => 'user/update'`: update a user
 * - `'DELETE users/<id>' => 'user/delete'`: delete a user
 * - `'GET,HEAD users/<id>' => 'user/view'`: return the details/overview/options of a user
 * - `'POST users' => 'user/create'`: create a new user
 * - `'GET,HEAD users' => 'user/index'`: return a list/overview/options of users
 * - `'users/<id>' => 'user/options'`: process all unhandled verbs of a user
 * - `'users' => 'user/options'`: process all unhandled verbs of user collection
     *
     */

    /**
     * Sets the [[asArray]] property.
     * @param bool $value whether to return the query results in terms of arrays instead of Active Records.
     * @return $this the query object itself
     */
    public function asArray($value = true)
    {
        $this->asArray = $value;

        return $this;
    }

    /**
     * Finds the related records for the specified primary record.
     * This method is invoked when a relation of an ActiveRecord is being accessed in a lazy fashion.
     * @param string $name the relation name
     * @param ActiveResourceInterface $model the primary model
     * @return mixed the related record(s)
     * @throws BadResponseException
     * @throws Exception
     * @throws InvalidConfigException
     * @throws ReflectionException
     */
    public function findFor($name, $model)
    {
        if (method_exists($model, 'get' . $name)) {
            $method = new ReflectionMethod($model, 'get' . $name);
            $realName = lcfirst(substr($method->getName(), 3));
            if ($realName !== $name) {
                throw new InvalidArgumentException('Relation names are case sensitive. ' . get_class($model) . " has a relation named \"$realName\" instead of \"$name\".");
            }
        }

        return $this->multiple ? $this->all() : $this->one();
    }

    /**
     * Returns the number of records.
     * @param string $q the COUNT expression. Defaults to '*'.
     * @param Client $client the database connection used to execute the query.
     * If this parameter is not given, the `db` application component will be used.
     * @return int number of records.
     * @throws BadResponseException
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function count($q = '*', $client = null)
    {
        $this->perPage = 1;
        $request = $this->createRequest($client);
        foreach ($this->getItems($request) as [$item, $count]) {
            //bigint???
            return (int)$count;
        }

        return 0;
    }

    /**
     * Returns a value indicating whether the query result contains any row of data.
     * @param Client $client the rest api used to execute the query.
     * If this parameter is not given, the `db` application component will be used.
     * @return bool whether the query result contains any row of data.
     * @throws BadResponseException
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function exists($client = null)
    {
        return (bool)$this->count();
    }

    /**
     * Specifies the relation associated with the junction table for use in relational query.
     * @param string $relationName the relation name. This refers to a relation declared in the [[ActiveRelationTrait::primaryModel|primaryModel]] of the relation.
     * @param callable $callable a PHP callback for customizing the relation associated with the junction table.
     * Its signature should be `function($query)`, where `$query` is the query to be customized.
     * @return $this the relation object itself.
     */
    public function via($relationName, callable $callable = null)
    {
        // TODO: Implement via() method.
    }

    /**
     * @param Request $request
     * @return Generator
     * @throws BadResponseException
     * @throws Exception
     */
    private function getItems(Request $request): Generator
    {
        do {
            $response = $request->send();
            if (!$response->isOk) {
                throw new BadResponseException($response, $response->toString(), $response->getStatusCode());
            }
            /** @var ActiveResponse $data */
            $data = $response->getData();
            $request->setFullUrl($data->linkNext);

            //i am not sure about this
            yield [$data->items, $data->totalCount, $data->currentPage, $data->perPage, $data->pageCount];
        } while ($data->linkNext);
    }

    /**
     * @param array $expand
     * @param ActiveResource[] $models
     * @param $rows
     * @throws InvalidConfigException
     */
    private function findExpand(array $expand, array $models, $rows)
    {
        $primaryModel = reset($models);
        if (!$primaryModel instanceof ActiveResourceInterface) {
            /* @var $modelClass ActiveResourceInterface */
            $modelClass = $this->modelClass;
            $primaryModel = $modelClass::instance();
        }
        $relations = $this->normalizeRelations($primaryModel, $expand);
        /* @var $relation ActiveResourceQuery */
        foreach ($relations as $name => $relation) {
            if ($relation->asArray === null) {
                // inherit asArray from primary query
                $relation->asArray($this->asArray);
            }

            foreach ($models as $model) {
                $expandRow = $model->getOriginalRequest()[$name] ?? null;
                if(!$relation->multiple) {
                    $expandRow = [$expandRow];
                }

                $relatedModels = $relation->createModels($expandRow);

                if(!$relation->multiple) {
                    $relatedModels = reset($relatedModels);
                }

                $model->populateRelation($name, $relatedModels);
            }
        }
    }


    /**
     * @param ActiveResourceInterface $model
     * @param array $with
     * @return ActiveResourceQuery[]
     */
    private function normalizeRelations($model, $with)
    {
        $relations = [];
        foreach ($with as $name => $callback) {
            if (is_int($name)) {
                $name = $callback;
                $callback = null;
            }

            if (!isset($relations[$name])) {
                $relation = $model->getRelation($name);
                $relation->primaryModel = null;
                $relations[$name] = $relation;
            }
        }

        return $relations;
    }

    private function isPopulated($rows) {
        return $rows[0] instanceof ActiveResource;
    }
}
