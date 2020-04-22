<?php
namespace p4it\rest\client;

use yii\base\BaseObject;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;
use yii\httpclient\Request;

/**
 * User: papppeter
 * Date: 13/09/2019
 * Time: 15:36
 */

class ActiveHttpClient extends Component {

    /**
     * @var Client
     */
    public $client;

    /**
     * @return Request
     * @throws InvalidConfigException
     */
    public function createRequest() {
        /** @var Client $client */
        $client = \Yii::createObject($this->client);
        return $client->createRequest();
    }

    public function buildUrl(ActiveResourceQuery $resourceQuery) {
        $resourceQuery->prepare();
        
        /* @var $modelClass ActiveResource */
        $modelClass = $resourceQuery->modelClass;

        $url = [
            $modelClass::resourceName()
        ];

        if($resourceQuery->select !== null) {
            $url[$resourceQuery->fieldParam] = implode(',',$resourceQuery->select);
        }

        if($resourceQuery->where !== null) {
            $url[$resourceQuery->filterParam] = $resourceQuery->where;
        }

        if($resourceQuery->perPage !== null) {
            $url[$resourceQuery->perPageParam] = $resourceQuery->perPage;
        }

        if($resourceQuery->expand !== null) {
            $url[$resourceQuery->expandParam] = implode(',', $resourceQuery->expand);
        }

        if($resourceQuery->orderBy !== null) {
            $url[$resourceQuery->sortParam] = implode(',', $resourceQuery->orderBy);
        }

        if($resourceQuery->limit !== null) {
            throw new \InvalidArgumentException('Limit is not implemented yet');
        }

        return $url;
    }

    public function buildUpdateOneUrl(ActiveResourceQuery $resourceQuery, $primaryKeys) {
        /* @var $modelClass ActiveResource */
        $modelClass = $resourceQuery->modelClass;

        $url = [
            $modelClass::resourceName().'/'.implode(',',$primaryKeys)
        ];

        return $url;
    }

    public function buildInsertUrl(ActiveResourceQuery $resourceQuery) {
        /* @var $modelClass ActiveResource */
        $modelClass = $resourceQuery->modelClass;

        $url = [
            $modelClass::resourceName()
        ];

        return $url;
    }

}
