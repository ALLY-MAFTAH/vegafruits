<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'stock_id',
        'name',
        'type',
        'volume',
        'unit',
        'selling_price',
        'has_discount',
        'photo',
        'status',

    ];

    protected $dates = [
        'deleted_at'
    ];

    public function stock()
    {
        return  $this->belongsTo(Stock::class,'stock_id');
    }
}
