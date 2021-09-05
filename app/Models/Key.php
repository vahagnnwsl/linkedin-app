<?php

namespace App\Models;

use App\Repositories\CompanyRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Key extends Model
{

    /**
     * @var string[] 
     */
    protected  $fillable = [
        'name',
        'status',
        'proxy_id',
        'country_id',
        'account_id'
    ];

    /**
     * @return BelongsToMany
     */
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_search_keys', 'key_id', 'company_id');
    }

    /**
     * @return Collection
     */
    public function getParsedCompaniesAttribute(): Collection
    {
        return $this->companies()->where('is_parsed', CompanyRepository::$PARSED_SUCCESS_STATUS)->get();
    }

    /**
     * @return Collection
     */
    public function getNoParsedCompaniesAttribute(): Collection
    {
        return $this->companies()->where('is_parsed', CompanyRepository::$NO_PARSED_STATUS)->get();
    }

    /**
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return belongsToMany
     */
    public function accounts(): belongsToMany
    {
        return $this->belongsToMany(Account::class, 'keys_accounts', 'key_id', 'account_id');
    }

    /**
     * @param string $relationName
     * @return mixed
     */
    public function getRandomRelation(string $relationName)
    {
        return $this->$relationName()->inRandomOrder()->first();
    }


}
