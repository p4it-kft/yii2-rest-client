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

class File extends ActiveResource {

    public function attributes()
    {
        return [
            'id',
            'history_id',
            'created_at',
            'updated_at'
        ];
    }

    public static function primaryKey()
    {
        return ['id'];
    }

    /**
     * @return ActiveResourceQuery|FileQuery
     */
    public static function find()
    {
        return new FileQuery(static::class);
    }
}
