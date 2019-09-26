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
        /* @var $modelClass ActiveResource */
        $modelClass = $resourceQuery->modelClass;

        $url = [
            $modelClass::resourceName()
        ];

        if($resourceQuery->select) {
            $url[$resourceQuery->fieldParam] = implode(',',$resourceQuery->select);
        }

        if($resourceQuery->where) {
            $url[$resourceQuery->filterParam] = $resourceQuery->where;
        }

        if($resourceQuery->perPage) {
            $url[$resourceQuery->perPageParam] = $resourceQuery->perPage;
        }

        if($resourceQuery->expand) {
            $url[$resourceQuery->expandParam] = implode(',', $resourceQuery->expand);
        }

        if($resourceQuery->orderBy) {
            $url[$resourceQuery->sortParam] = implode(',', $resourceQuery->orderBy);
        }

        //debug session
        $url['XDEBUG_SESSION_START'] = '14098';

        return $url;
    }

    public function buildUpdateOneUrl(ActiveResourceQuery $resourceQuery, $primaryKeys) {
        /* @var $modelClass ActiveResource */
        $modelClass = $resourceQuery->modelClass;

        $url = [
            $modelClass::resourceName().'/'.implode(',',$primaryKeys)
        ];

        //debug session
        $url['XDEBUG_SESSION_START'] = '14098';

        return $url;
    }

}