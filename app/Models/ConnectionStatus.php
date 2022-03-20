<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConnectionStatus extends Model
{
    protected $table = 'connection_statuses';
    use HasFactory, SoftDeletes;


    /**
     * @var string[]
     */
    protected $fillable = [
        'connection_id',
        'morphClass',
        'morphedModel',
        'text'
    ];

    /**
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'connection_status_categories', 'connection_status_id', 'category_id');
    }

}
