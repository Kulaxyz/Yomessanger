<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contacts';
    protected $fillable = ['phone', 'user_id', 'name', 'surname'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
