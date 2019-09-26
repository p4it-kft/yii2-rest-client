<?php

namespace tests\resources;

use p4it\rest\client\ActiveResource;
use p4it\rest\client\ActiveResourceQuery;
use p4it\rest\client\ActiveResourceQueryInterface;

/**
 * @property int $room_help_id
 * @property int $room_type_id
 * @property int $parent_room_help_id
 * @property string $name
 * @property string $description
 * @property int $is_active
 * @property int $sort
 * @property int $is_checkpoint
 * @property string $external_key
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 *
 * @property RoomType $roomType
 * @property RoomHelpText[] $roomHelpTexts
 */

class RoomHelp extends ActiveResource {

    public function attributes()
    {
        return [
            'room_help_id',
            'room_type_id',
            'parent_room_help_id',
            'name',
            'description',
            'is_active',
            'sort',
            'is_checkpoint',
            'external_key',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ];
    }

    public static function primaryKey()
    {
        return ['room_help_id'];
    }

    /**
     * @return ActiveResourceQuery|RoomHelpQuery
     */
    public static function find()
    {
        return new RoomHelpQuery(static::class);
    }

    /**
     * @return ActiveResourceQueryInterface|RoomTypeQuery
     */
    public function getRoomType() {
        return $this->hasOne(RoomType::class, ['room_type_id' => 'room_type_id']);
    }

    /**
     * @return ActiveResourceQueryInterface|RoomHelpTextQuery
     */
    public function getRoomHelpTexts() {
        return $this->hasMany(RoomHelpText::class, ['room_help_id' => 'room_help_id']);
    }
}
