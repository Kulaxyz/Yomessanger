<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = ['user_id', 'uuid', 'settings', 'verified_at'];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'verified_at' => 'datetime',

    ];

}
