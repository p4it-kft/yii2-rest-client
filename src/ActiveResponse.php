<?php
namespace p4it\rest\client;

use yii\base\BaseObject;

/**
 * JsonParser parses HTTP message content as JSON.
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 2.0
 */
class ActiveResponse extends BaseObject {
    public $totalCount;

    public $pageCount;

    public $currentPage;

    public $perPage;

    /**
     * @var array
     */
    public $items = [];

    public $linkSelf;

    /**
     * we will iterate till linkNext exists!
     *
     * @var string
     */
    public $linkNext;

    public $content;
}