<?php

namespace App\Models;

use App\Repositories\ConnectionRequestRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AccountConversationsLimit extends Model
{
    use HasFactory;

    protected $table = 'accounts_conversations_limit';

    protected $guarded = [];



}
