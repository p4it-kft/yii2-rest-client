<?php
namespace p4it\rest\client\tests\unit;

use Codeception\PHPUnit\TestCase;
use p4it\rest\client\BadResponseException;
use tests\resources\File;
use tests\resources\PhotoRoomSlotHasFile;
use tests\resources\Room;
use tests\resources\RoomType;
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

        $model = PhotoRoomSlotHasFile::findOne(['AND', 'room_slot_id' => 112235, 'file_history_id' => 68472]);
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

            break;
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

            break;
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
     * Testing expand
     */
    public function testExpand()
    {
        $item = PhotoRoomSlotHasFile::find()->expand(['file'])->where(['updated_at' => ['gt' => '2019-08-10 00:00:00']])->one();

        self::assertInstanceOf(File::class, $item->file, 'Wrong instance');

        $item = RoomType::find()->expand(['rooms'])->one();

        self::assertInstanceOf(Room::class, $item->rooms[0], 'Wrong instance');

    }

    /**
     * Testing relations
     */
    public function testRelations()
    {
        $item = RoomType::find()->one();
        self::assertInstanceOf(Room::class, $item->rooms[0], 'Wrong instance');

        $item = Room::find()->one();
        self::assertInstanceOf(RoomType::class, $item->roomType, 'Wrong instance');
    }

    /**
     * Testing Count, exists, asArray
     */
    public function testCountExistsAsArray()
    {
        $count = RoomType::find()->count();
        self::assertIsInt($count, 'Wrong response');
        self::assertGreaterThan(1, $count, 'We received a wrong count');

        $bool = Room::find()->exists();
        self::assertIsBool($bool, 'Wrong response');
        self::assertTrue($bool, 'Wrong response');

        $item = Room::find()->asArray()->one();
        self::assertIsArray($item, 'Wrong response');
    }

    /**
     * Testing update
     */
    public function testUpdate()
    {
        $item = RoomType::find()->one();
        self::assertInstanceOf(RoomType::class, $item, 'Wrong instance');

        $item->is_sellable = 0;
        $saved = $item->save();

        self::assertTrue($saved, 'Could not save');

        $item->is_sellable = 1;
        $saved = $item->save();

        self::assertTrue($saved, 'Could not save');
    }

    /**
     * Testing insert
     */
    public function testInsert()
    {
        $item = new RoomType([
            'name' => 'test',
            'color_class' => 'color',
            'min_persons' => 1,
            'max_persons' => 4,
            'is_sellable' => 0,
            'short_name' => 'short',
        ]);

        $saved = $item->save();

        self::assertTrue($saved, 'Could not save');

        $deleted = $item->delete();

        self::assertTrue($deleted, 'Could not delete');
    }

    /**
     * Testing validation error
     */
    public function testValidationError()
    {
        $item = new RoomType([
            'name' => 'test',
            'color_class' => 'color',
            'min_persons' => 1,
            'max_persons' => 4,
            'is_sellable' => 0,
        ]);

        $saved = $item->save();

        self::assertTrue($saved, 'Could not save');

        $deleted = $item->delete();

        self::assertTrue($deleted, 'Could not delete');
    }
}