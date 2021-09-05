<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Position extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'connection_id',
        'company_id',
        'start_date',
        'end_date',
        'sort',
        'is_current',
    ];


    protected $appends = ['duration'];

    public function getDurationAttribute(): string
    {

        if ($this->getAttribute('start_date')) {

            $to = Carbon::createFromFormat('Y-m-d H:s:i', $this->getAttribute('start_date'));
            $from = $this->getAttribute('end_date') ? $this->getAttribute('end_date') : Carbon::now();
            $from = Carbon::createFromFormat('Y-m-d H:s:i', $from);

            $diff = $to->diff($from);

            $str = '';

            if ($diff->y) {
                $str .= $diff->y . ' years ';
            }
            if ($diff->m) {
                $str .= $diff->m . ' months';

            }
            return $str;
        }
        return '';
    }


    /**
     * @var string[]
     */
    protected $casts = [
        'start_date' => 'datetime:F  Y',
        'end_date' => 'datetime:F Y',
    ];


    /**
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

}
