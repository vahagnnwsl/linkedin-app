<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class Moderator  extends Authenticatable
{
    protected $guard = 'moderator';

    protected  $fillable = [
        'email',
        'password',
        'password_non_hash'
    ];

    public function scopeByRating($query)
    {
        $query->selectRaw("@row_number:=@row_number+1 AS position, moderators.*")
            ->from(DB::raw("moderators, (SELECT @row_number:=0) AS t"))
            ->orderBy('id', 'ASC');
    }
}
