<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TurbojetVirtualReport extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_DATE = 'date';
    const COLUMN_NAME = 'name';
    const COLUMN_PATH = 'path';
    const COLUMN_RECORD_COUNT = 'record_count';

    protected $dates = [
        self::COLUMN_DATE,
    ];

    public function getReportStoragePath()
    {
        return storage_path('app/' . $this->{self::COLUMN_PATH});
    }

}
