<?php

namespace App\Models;

use App\Repositories\CompanyRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Key extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];


    /**
     * @return BelongsToMany
     */
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_search_keys', 'key_id','company_id');
    }


    /**
     * @return Collection
     */
    public function getParsedCompaniesAttribute(): Collection
    {
        return $this->companies()->where('is_parsed',CompanyRepository::$PARSED_SUCCESS_STATUS)->get();
    }

    /**
     * @return Collection
     */
    public function getNoParsedCompaniesAttribute(): Collection
    {
        return $this->companies()->where('is_parsed',CompanyRepository::$NO_PARSED_STATUS)->get();
    }

}
