<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Connection extends Model
{
    use HasFactory;

    protected $fillable = [
        'entityUrn',
        'firstName',
        'lastName',
        'publicIdentifier',
        'occupation',
        'image',
        'data'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'data' => 'array',
    ];


    /**
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->firstName} {$this->lastName}";
    }

    /**
     * @return BelongsToMany
     */
    public function accounts(): BelongsToMany
    {
        return $this->belongsToMany(Account::class, 'account_connections', 'connection_id', 'account_id');
    }

    /**
     * @return BelongsToMany
     */
    public function keys(): BelongsToMany
    {
        return $this->belongsToMany(Key::class, 'connections_keys', 'connection_id', 'key_id');
    }

}
