<?php

namespace App\Models;

use App\Repositories\CompanyRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Job extends Model
{

    public function getDisplayAttribute()
    {
        $payload = $this->getAttribute('payload');
        $payload = json_decode($payload);
        $universalize = unserialize($payload->data->command);
        return $universalize->displayAttribute();
    }
}
