<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Post extends Model
{
    /* 기본키 설정 */
    protected $primaryKey = 'id';

    protected $guarded = [];
}
