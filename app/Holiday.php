<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{

    public function user() {
        return $this->belongsTo('App\User');
    }

}
