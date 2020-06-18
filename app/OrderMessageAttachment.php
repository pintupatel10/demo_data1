<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderMessageAttachment extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_ORDER_MESSAGE_ID = 'order_message_id';
    const COLUMN_NAME = 'name';
    const COLUMN_PATH = 'path';

    const STORAGE_FOLDER = 'app/message-attachments';

    public function order_message()
    {
        return $this->belongsTo('App\OrderMessage');
    }

    public function getStoragePath()
    {
        return storage_path(self::STORAGE_FOLDER . '/' . $this->{self::COLUMN_PATH});
    }
}
