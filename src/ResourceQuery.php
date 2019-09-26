<?php
namespace p4it\rest\client;

use yii\base\Component;
use yii\httpclient\Client;

/**
 * User: papppeter
 * Date: 13/09/2019
 * Time: 15:41
 */

class ResourceQuery extends Component implements ResourceQueryInterface {
    use ResourceQueryTrait;

    /**
     * @var array the columns being selected. For example, `['id', 'name']`.
     * This is used to construct the SELECT clause in a SQL statement. If not set, it means selecting all columns.
     * @see select()
     */
    public $select;

    /**
     * @var array a list of expands, if it is a relation it will be populated into relation
     */
    public $expand;
    
    /**
     * Executes query and returns a single row of result.
     * @param Client $client the DB connection used to create the DB command.
     * If `null`, the DB connection returned by [[ActiveQueryTrait::$modelClass|modelClass]] will be used.
     * @return ActiveResourceInterface|array|null a single row of query result. Depending on the setting of [[asArray]],
     * the query result may be either an array or an ActiveRecord object. `null` will be returned
     * if the query results in nothing.
     */
    public function one($client = null)
    {
        // TODO: Implement one() method.
    }

    /**
     * Finds the related records for the specified primary record.
     * This method is invoked when a relation of an ActiveRecord is being accessed in a lazy fashion.
     * @param string $name the relation name
     * @param ActiveResourceInterface $model the primary model
     * @return mixed the related record(s)
     */
    public function findFor($name, $model)
    {
        // TODO: Implement findFor() method.
    }

    /**
     * Executes the query and returns all results as an array.
     * @param Client $client the rest api used to execute the query.
     * If this parameter is not given, the `db` application component will be used.
     * @return array the query results. If the query results in nothing, an empty array will be returned.
     */
    public function all($client = null)
    {
        return [];
    }

    /**
     * Returns the number of records.
     * @param string $q the COUNT expression. Defaults to '*'.
     * @param Client $client the rest api used to execute the query.
     * If this parameter is not given, the `db` application component will be used.
     * @return int number of records.
     */
    public function count($q = '*', $client = null)
    {
        // TODO: Implement count() method.
    }

    /**
     * Returns a value indicating whether the query result contains any row of data.
     * @param Client $client the rest api used to execute the query.
     * If this parameter is not given, the `db` application component will be used.
     * @return bool whether the query result contains any row of data.
     */
    public function exists($client = null)
    {
        // TODO: Implement exists() method.
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
        if ($this->indexBy === null) {
            return $rows;
        }
        $result = [];
        foreach ($rows as $row) {
            $result[ArrayHelper::getValue($row, $this->indexBy)] = $row;
        }

        return $result;
    }

    /**
     * we can specify which fields need to be returned
     *
     * @param array $columns
     * @return ResourceQuery
     */
    public function select(array $columns): ResourceQuery
    {
        $this->select = $columns;
        return $this;
    }

    /**
     * @param array $columns
     * @return ResourceQuery
     * @see select()
     */
    public function addSelect(array $columns): ResourceQuery
    {
        if ($this->select === null) {
            return $this->select($columns);
        }
        
        $this->select = array_merge($this->select, $columns);

        return $this;
    }

    /**
     * @param array $expand
     * @return ResourceQuery
     */
    public function expand(array $expand): ResourceQuery
    {
        $this->expand = $expand;
        return $this;
    }

    /**
     * @param array $expands
     * @return ResourceQuery
     */
    public function addExpand(array $expands): ResourceQuery
    {
        if ($this->expand === null) {
            return $this->expand($expands);
        }

        $this->expand = array_merge($this->expand, $expands);

        return $this;
    }
}