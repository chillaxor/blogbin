<?php
/*
 * @Author: jinzhi
 * @email: <chenxinbin@linghit.com>
 * @Date: 2021-01-06 17:25:14
 * @Description: Description
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRelation extends Model
{
    protected $table = 'user_relation';
    public function group()
    {
        return $this->hasMany(\App\UserGroup::class, 'id', 'group_id');
    }
    public function friends()
    {
        return $this->hasOne(\App\User::class, 'id', 'friend_id');
    }
}
