<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;

class Boards extends Model
{
    use HasFactory, softDeletes;

    protected $guarded = ['id', 'created_at'];

    protected $dates = ['deleted_at']; // softDeletes를 자동적으로 사용해주기 위해서 적어주기
}
