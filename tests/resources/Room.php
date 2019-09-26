<?php

namespace tests\resources;

use p4it\rest\client\ActiveResource;
use p4it\rest\client\ActiveResourceQuery;
use p4it\rest\client\ActiveResourceQueryInterface;

/**
 * @property int $room_id
 * @property int $room_type_id
 * @property string $type_short_name_postfix
 * @property string $type_name_postfix ide adjuk meg a unit 1,2,3,4 -et unit nélkül
 * @property int $sort
 * @property string $active_from
 * @property string $active_to
 * @property int $is_active
 * @property int $is_wheelchair
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 *
 * @property RoomType $roomType
 */

class Room extends ActiveResource {

    public function attributes()
    {
        return [
            'room_id',
            'room_type_id',
            'type_short_name_postfix',
            'type_name_postfix',
            'sort',
            'active_from',
            'active_to',
            'is_active',
            'is_wheelchair',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ];
    }

    public static function primaryKey()
    {
        return ['room_id'];
    }

    /**
     * @return ActiveResourceQuery|RoomQuery
     */
    public static function find()
    {
        return new RoomQuery(static::class);
    }

    /**
     * @return ActiveResourceQueryInterface|RoomTypeQuery
     */
    public function getRoomType() {
        return $this->hasOne(RoomType::class, ['room_type_id' => 'room_type_id']);
    }
}
