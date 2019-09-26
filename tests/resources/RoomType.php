<?php

namespace tests\resources;

use p4it\rest\client\ActiveResource;
use p4it\rest\client\ActiveResourceQuery;
use p4it\rest\client\ActiveResourceQueryInterface;

/**
 * @property int $room_type_id
 * @property string $name
 * @property string $short_name
 * @property string $description
 * @property string $short_description
 * @property int $difficulty
 * @property string $color_class
 * @property int $duration
 * @property int $is_sellable
 * @property int $min_persons
 * @property int $max_persons
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 *
 * @property Room[] $rooms
 * @property RoomHelp[] $roomHelps
 */

class RoomType extends ActiveResource {

    public function attributes()
    {
        return [
            'room_type_id',
            'name',
            'short_name',
            'description',
            'short_description',
            'difficulty',
            'color_class',
            'duration',
            'is_sellable',
            'min_persons',
            'max_persons',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ];
    }

    public static function primaryKey()
    {
        return ['room_type_id'];
    }

    /**
     * @return ActiveResourceQuery|RoomTypeQuery
     */
    public static function find()
    {
        return new RoomTypeQuery(static::class);
    }


    /**
     * @return ActiveResourceQueryInterface|RoomQuery
     */
    public function getRooms() {
        return $this->hasMany(Room::class, ['room_type_id' => 'room_type_id']);
    }

    /**
     * @return ActiveResourceQueryInterface|RoomHelpQuery
     */
    public function getRoomHelps() {
        return $this->hasMany(RoomHelp::class, ['room_type_id' => 'room_type_id']);
    }
}
