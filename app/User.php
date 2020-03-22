<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'name', 'username', 'surname', 'phone', 'avatar', 'is_blocked', 'last_online_at', 'status_text', 'referred_by',
    ];

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function chats()
    {
        return $this->belongsToMany(Chat::class, 'participants');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function getNameAttribute($value)
    {
        $auth = User::find(4);
        if ($this->id != $auth->id) {
            foreach ($auth->contacts as $contact) {
                if ($contact->phone == $this->phone) {
                    return $contact->name;
                }
            }
        }
        return $value;
    }

}
