<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{

    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'entityUrn',
        'password',
        'login',
        'full_name',
        'lastActivityAt',
        'status',
        'type',
        'limit_connection_request',
        'limit_conversation',
    ];


    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_accounts', 'account_id', 'user_id');
    }

    /**
     * @return BelongsToMany
     */
    public function connections(): BelongsToMany
    {
        return $this->belongsToMany(Connection::class, 'account_connections', 'account_id', 'connection_id');
    }

    /**
     * @return HasMany
     */
    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class, 'account_id', 'id');
    }


    /**
     * @return belongsToMany
     */
    public function proxies(): belongsToMany
    {
        return $this->belongsToMany(Proxy::class, 'accounts_proxies', 'account_id', 'proxy_id');
    }

    /**
     * @return mixed|null
     */
    public function getRandomFirstProxy()
    {
        return $this->proxies()->inRandomOrder()->first();
    }

    /**
     * @return int
     */
    public function getSendRequestCount(): int
    {
        return $this->hasMany(ConnectionRequest::class)->whereDate('created_at',date('Y-m-d'))->count();
    }

    /**
     * @return int
     */
    public function getConversationCount(): int
    {
        return $this->hasMany(AccountConversationsLimit::class)->whereDate('created_at',date('Y-m-d'))->count();
    }

}
