<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{


    public function getCreatedAtAttribute($date)
    {
        $c = new Carbon($date);
        $c->setTimezone($this->user->local_timezone);
        return $c->format('jS \\of F Y H:i');
    }

    public function getResolvedAtAttribute($date)
    {
        $c = new Carbon($date);
        $c->setTimezone($this->user->local_timezone);
        return $c->format('jS \\of F Y H:i');
    }


    function user() {
        return $this->belongsTo('App\User');
    }

    function author() {
        return $this->belongsTo('App\User', 'author_id');
    }
}
