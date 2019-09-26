<?php

namespace tests\resources;

use p4it\rest\client\ActiveResource;
use p4it\rest\client\ActiveResourceQuery;

/**
 * @property int $room_help_text_id
 * @property int $room_help_id
 * @property string $help_text
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 *
 * @property RoomHelp $roomHelp
 */

class RoomHelpText extends ActiveResource {

    public function attributes()
    {
        return [
            'room_help_text_id',
            'room_help_id',
            'help_text',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ];
    }

    public static function primaryKey()
    {
        return ['room_help_text_id'];
    }

    /**
     * @return ActiveResourceQuery|RoomHelpTextQuery
     */
    public static function find()
    {
        return new RoomHelpTextQuery(static::class);
    }

    /**
     * @return \p4it\rest\client\ActiveResourceQueryInterface|RoomHelpQuery
     */
    public function getRoomHelp() {
        return $this->hasOne(RoomHelp::class, ['room_help_id' => 'room_help_id']);
    }
}
