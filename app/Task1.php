<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task1 extends Model
{
    protected $table = 'tasks';
    protected $fillable = ['name'];

    public static function forUser(User $user)
    {
        return self::where('user_id', $user->id)->oldest()->get();
    }
}
