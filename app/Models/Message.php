<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Message extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'conversation_id',
        'connection_id',
        'user_id',
        'account_id',
        'text',
        'entityUrn',
        'date',
        'status',
        'event',
        'is_delete',
        'media',
        'attachments',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'date' => 'datetime:Y-m-d H:m',
        'media' => 'array',
        'attachments' => 'array',
    ];

    /**
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    /**
     * @return HasOne
     */
    public function connection(): HasOne
    {
        return $this->hasOne(Connection::class,'id','connection_id');
    }

    /**
     * @return HasOne
     */
    public function conversation(): HasOne
    {
        return $this->hasOne(Conversation::class, 'id', 'conversation_id');
    }


    public static function boot(): void
    {
        parent::boot();

        static::created(function ($model) {
            $model->conversation->update(['lastActivityAt' => Carbon::now()->toDateTimeString()]);
        });
    }
}
