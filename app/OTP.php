<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
    protected $table = 'o_t_p_s';
    protected $dates = ['expires_at'];
}
