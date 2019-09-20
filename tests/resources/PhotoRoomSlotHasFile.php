<?php

namespace tests\resources;

use p4it\rest\client\ActiveResource;
use p4it\rest\client\ActiveResourceQuery;

/**
 * @property $room_slot_id
 * @property $type
 * @property $file_history_id
 * @property $created_at
 * @property $updated_at'
 */

class PhotoRoomSlotHasFile extends ActiveResource {

    public function attributes()
    {
        return [
            'room_slot_id',
            'type',
            'file_history_id',
            'created_at',
            'updated_at'
        ];
    }

    public static function primaryKey()
    {
        return ['room_slot_id', 'file_history_id'];
    }

    /**
     * @return ActiveResourceQuery|PhotoRoomSlotHasFileQuery
     */
    public static function find()
    {
        return new PhotoRoomSlotHasFileQuery(static::class);
    }

    public function getFile() {
        return $this->hasOne(File::class, ['history_id' => 'file_history_id']);
    }
}
