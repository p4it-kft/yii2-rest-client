<?php
namespace p4it\rest\client\tests\unit;

use Codeception\PHPUnit\TestCase;
use p4it\rest\client\BadResponseException;
use tests\resources\PhotoRoomSlotHasFile;
use Yii;
use yii\httpclient\Client;

/**
 * Unit-tests for SentryTarget
 *
 * @mixin TestCase
 */
class ActiveResourceTest extends TestCase
{

    protected function setUp()
    {
        parent::setUp();
    }



    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @return Client
     */
    protected function client() {
        return Yii::$app->httpClient;
    }


    /**
     * Testing setup
     */
    public function testHttpClientSetup()
    {
        $response = $this->client()->createRequest()->setUrl('photo-room-slot-has-files')->setMethod('GET')->send();
        self::assertTrue($response->isOk, 'Response was not OK');
    }

    /**
     * Testing get one value
     */
    public function testOne()
    {

        $model = PhotoRoomSlotHasFile::findOne(['AND', 'room_slot_id' => 165270, 'file_history_id' => 79919]);
        self::assertInstanceOf(PhotoRoomSlotHasFile::class, $model, 'Wrong instance received');
    }

    /**
     * Testing get all value
     */
    public function testAll()
    {
        $models = PhotoRoomSlotHasFile::findAll(['updated_at' => ['gt' => '2019-08-10 00:00:00']]);
        self::assertIsArray($models, 'We did not received arrays');
        self::assertInstanceOf(PhotoRoomSlotHasFile::class, $models[0], 'First element is wrong instance');
    }

    /**
     * Testing bad request
     */
    public function testBadRequest()
    {
        $exception = null;
        try {
            PhotoRoomSlotHasFile::findAll(['updated_at' => ['gt' => '2019-08-10']]);
        } catch (\Exception $exception) {
            self::assertInstanceOf(BadResponseException::class, $exception, 'Wrong exception is thrown');
            self::assertEquals(422,$exception->getCode(),  'Wrong exception code received');
        }

        self::assertNotEmpty($exception, 'No exception is thrown');
    }

    /**
     * Testing batch
     */
    public function testBatch()
    {
        $items = [];

        foreach (PhotoRoomSlotHasFile::find()->where(['updated_at' => ['gt' => '2019-08-10 00:00:00']])->batch(5) as $items) {
            self::assertIsArray($items, 'We received not an array');
            self::assertInstanceOf(PhotoRoomSlotHasFile::class, $items[0], 'Wrong instance');
        };

        self::assertNotEmpty($items, 'No results received');
    }

    /**
     * Testing batch
     */
    public function testEach()
    {
        $item = null;

        foreach (PhotoRoomSlotHasFile::find()->where(['updated_at' => ['gt' => '2019-08-10 00:00:00']])->each(5) as $item) {
            self::assertIsNotArray($item, 'We received an array');
            self::assertInstanceOf(PhotoRoomSlotHasFile::class, $item, 'Wrong instance');
        };

        self::assertNotEmpty($item, 'No results received');
    }

    /**
     * Testing batch
     */
    public function testOrderBy()
    {
        $itemsAsc = PhotoRoomSlotHasFile::find()->where(['updated_at' => ['gt' => '2019-08-10 00:00:00']])->orderBy('updated_at')->one();
        $itemsDesc = PhotoRoomSlotHasFile::find()->where(['updated_at' => ['gt' => '2019-08-10 00:00:00']])->orderBy('-updated_at')->one();

        self::assertNotEquals($itemsAsc->updated_at, $itemsDesc->updated_at, 'Update at field should not be equal');
    }

    /**
     * Testing batch
     */
    public function testExpand()
    {
        $item = PhotoRoomSlotHasFile::find()->expand(['file'])->where(['updated_at' => ['gt' => '2019-08-10 00:00:00']])->one();

    }
}