<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tickethighlight extends Model
{
    use SoftDeletes;
    protected $table="ticket_highlights";
    protected $fillable = [
        'detailid','title','content'
    ];

    public function TicketList()
    {
        return $this->belongsTo('App\TicketList', 'id');
    }
}
