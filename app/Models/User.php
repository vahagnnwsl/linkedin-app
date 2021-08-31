<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'status',
        'email',
        'password',
        'linkedin_login',
        'linkedin_password',
        'linkedin_fullName',
        'linkedin_entityUrn',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * @return BelongsToMany
     */
    public function conversations(): BelongsToMany
    {
        return $this->belongsToMany(Conversation::class, 'user_conversations', 'user_id', 'conversation_id');
    }

    /**
     * @return BelongsToMany
     */
    public function keys(): BelongsToMany
    {
        return $this->belongsToMany(Key::class, 'user_keys', 'user_id', 'key_id');
    }



    /**
     * @return BelongsToMany
     */
    public function unRealAccounts(): BelongsToMany
    {
        return $this->belongsToMany(Account::class, 'users_un_real_accounts', 'user_id', 'account_id');
    }

    /**
     * @return BelongsToMany
     */
    public function accounts(): BelongsToMany
    {
        return $this->belongsToMany(Account::class, 'user_accounts', 'user_id', 'account_id');
    }

    /**
     * @return mixed|null
     */
    public function getAccountAttribute()
    {
        return $this->accounts()->first();
    }

    /**
     * @return mixed|null
     */
    public function getRoleAttribute()
    {
        return $this->roles()->first();
    }
}
