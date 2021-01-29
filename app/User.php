<?php
/*
 * @Author: jinzhi
 * @email: <chenxinbin@linghit.com>
 * @Date: 2020-11-23 09:10:39
 * @Description: Description
 */

namespace App;


use App\UserRelation;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function relations()
    {
        return $this->hasMany(UserRelation::class, 'user_id', 'id');
    }

}
