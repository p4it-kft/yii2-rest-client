<?php
namespace p4it\rest\client;


use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\httpclient\ParserInterface;
use yii\httpclient\Response;

/**
 * JsonParser parses HTTP message content as JSON.
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 2.0
 */
class ResponseParser extends BaseObject implements ParserInterface
{

    public $totalCountKey = '_meta.totalCount';
    public $pageCountKey = '_meta.pageCount';
    public $currentPageKey = '_meta.currentPage';
    public $perPageKey = '_meta.perPage';
    public $itemsKey = 'items';
    public $linkSelfKey = '_links.self.href';
    public $linkNextKey = '_links.next.href';


    /**
     * {@inheritdoc}
     */
    public function parse(Response $response)
    {
        $content = Json::decode($response->getContent());

        $activeResponse = new ActiveResponse([
            'pageCount' => ArrayHelper::getValue($content, $this->pageCountKey),
            'totalCount' => ArrayHelper::getValue($content, $this->totalCountKey),
            'currentPage' => ArrayHelper::getValue($content, $this->currentPageKey),
            'perPage' => ArrayHelper::getValue($content, $this->perPageKey),
            'items' => ArrayHelper::getValue($content, $this->itemsKey, []),
            'linkSelf' => ArrayHelper::getValue($content, $this->linkSelfKey),
            'linkNext' => ArrayHelper::getValue($content, $this->linkNextKey),
            'content' => $content,
        ]);

        return $activeResponse;
    }
}