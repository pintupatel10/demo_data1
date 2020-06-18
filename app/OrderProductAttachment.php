<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderProductAttachment extends Model
{
    use SoftDeletes;
    use Auditable;

    const COLUMN_ID = 'id';
    const COLUMN_ORDER_PRODUCT_ID = 'order_product_id';
    const COLUMN_NAME = 'name';
    const COLUMN_PATH = 'path';

    const STORAGE_FOLDER = 'app/confirmation-attachments';

    public function order_product()
    {
        return $this->belongsTo('App\OrderProduct');
    }

    public function getStoragePath()
    {
        return storage_path(self::STORAGE_FOLDER . '/' . $this->{self::COLUMN_PATH});
    }

}
