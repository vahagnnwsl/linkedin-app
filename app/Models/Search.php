<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Search extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'params',
        'hash',
        'name',
        'user_id'
    ];

    protected $casts = [
        'params' => 'array',
    ];
    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
