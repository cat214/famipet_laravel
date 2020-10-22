<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * テーブルの主キー
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'icon',
    ];

    /**
     * ユーザーに関連する写真をとってくる
     */
    public function photos(){
        return $this->hasMany('App\Photo');
    }

    public function getPhotos($user_id)
    {
        return $this->find($user_id)->photos()->get(['url', 'filename']);
    }
}
