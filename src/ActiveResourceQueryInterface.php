<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace p4it\rest\client;

use yii\db\QueryInterface;
use yii\httpclient\Client;

/**
 * ActiveQueryInterface defines the common interface to be implemented by active record query classes.
 *
 * That are methods for either normal queries that return active records but also relational queries
 * in which the query represents a relation between two active record classes and will return related
 * records only.
 *
 * A class implementing this interface should also use [[ActiveQueryTrait]] and [[ActiveRelationTrait]].
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author Carsten Brandt <mail@cebe.cc>
 * @since 2.0
 */
interface ActiveResourceQueryInterface extends QueryInterface
{
    /**
     * Sets the [[asArray]] property.
     * @param bool $value whether to return the query results in terms of arrays instead of Active Records.
     * @return $this the query object itself
     */
    public function asArray($value = true);

    /**
     * Executes query and returns a single row of result.
     * @param Client $client the http connection used to create the request.
     * If `null`, the DB connection returned by [[ActiveQueryTrait::$modelClass|modelClass]] will be used.
     * @return ActiveResourceInterface|array|null a single row of query result. Depending on the setting of [[asArray]],
     * the query result may be either an array or an ActiveRecord object. `null` will be returned
     * if the query results in nothing.
     */
    public function one($client = null);

    /**
     * Sets the [[indexBy]] property.
     * @param string|callable $column the name of the column by which the query results should be indexed by.
     * This can also be a callable (e.g. anonymous function) that returns the index value based on the given
     * row or model data. The signature of the callable should be:
     *
     * ```php
     * // $model is an AR instance when `asArray` is false,
     * // or an array of column values when `asArray` is true.
     * function ($model)
     * {
     *     // return the index value corresponding to $model
     * }
     * ```
     *
     * @return $this the query object itself
     */
    public function indexBy($column);

    public function expand(array $expand);

    /**
     * Specifies the relation associated with the junction table for use in relational query.
     * @param string $relationName the relation name. This refers to a relation declared in the [[ActiveRelationTrait::primaryModel|primaryModel]] of the relation.
     * @param callable $callable a PHP callback for customizing the relation associated with the junction table.
     * Its signature should be `function($query)`, where `$query` is the query to be customized.
     * @return $this the relation object itself.
     */
    public function via($relationName, callable $callable = null);

    /**
     * Finds the related records for the specified primary record.
     * This method is invoked when a relation of an ActiveRecord is being accessed in a lazy fashion.
     * @param string $name the relation name
     * @param ActiveResourceInterface $model the primary model
     * @return mixed the related record(s)
     */
    public function findFor($name, $model);
}
