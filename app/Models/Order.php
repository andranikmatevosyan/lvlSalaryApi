<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed number
 * @property mixed full_name
 * @property mixed sum
 * @property mixed address
 */
class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'full_name',
        'sum',
        'address'
    ];
}
