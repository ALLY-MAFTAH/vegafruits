<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'customer_id',
        'stock_id',
        'good_id',
        'user_id',
        'seller',
        'name',
        'volume',
        'quantity',
        'unit',
        'price',
        'date',
        'type',
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function user()
    {
        return  $this->belongsTo(User::class);
    }
    public function customer()
    {
        return  $this->belongsTo(Customer::class,'customer_id');
    }
    public function stock()
    {
        return  $this->belongsTo(Stock::class, 'stock_id');
    }
    public function good()
    {
        return  $this->belongsTo(Good::class, 'good_id');
    }
}
