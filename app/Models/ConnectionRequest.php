<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ConnectionRequest extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'account_id',
        'connection_id',
        'user_id',
        'message',
        'status'
    ];



    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function connection(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Connection::class);
    }

    public function account(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
