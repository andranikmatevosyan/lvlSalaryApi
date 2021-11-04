<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Worker extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * @return HasMany
     */
    public function worker_statuses_data(): HasMany
    {
        return $this->hasMany(WorkerStatus::class, 'worker_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function salaries_data(): HasMany
    {
        return $this->hasMany(Salary::class, 'worker_id', 'id');
    }
}
