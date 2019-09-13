<?php
namespace p4it\rest\client;

use yii\base\Component;

/**
 * User: papppeter
 * Date: 13/09/2019
 * Time: 15:41
 */

class RestQuery extends Component {

    public $filter;

    public $expand;

    public $field;

    public $sort;
}