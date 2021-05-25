<?php

namespace App\Models;

use App\Repositories\ConnectionRequestRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'data',
        'is_parsed',
        'parsed_date'
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

    /**
     * @return bool
     */
    public function canSendConnectionRequest(): bool
    {
        $countAccounts = $this->accounts()->count();

        if ($countAccounts) {
            return false;
        }
        return true;
    }

    /**
     * @param int $id
     * @return HasOne
     */
    public function requestByAccount(int $id): HasOne
    {
       return $this->hasOne(ConnectionRequest::class,'connection_id','id')->where(['account_id'=>$id,'status'=>ConnectionRequestRepository::$PENDING_STATUS]);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function canWrite(int $id): bool
    {

        if ($this->accounts()->where('accounts.id', $id)->exists()) {
            return true;
        }

        return false;
    }

}
