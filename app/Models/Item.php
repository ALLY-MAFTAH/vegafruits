<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'unit',
        'type',
        'price',
        'volume',
        'stock_id',
        'quantity',
        'order_id',
        'product_id',

    ];

    protected $dates = [
        'deleted_at'
    ];

    public function order()
    {
        return  $this->belongsTo(Order::class, 'order_id');
    }
    public function customer()
    {
        return  $this->belongsTo(Customer::class, 'customer_id');
    }
    public function product()
    {
        return  $this->belongsTo(Product::class, 'product_id');
    }
    public function stock()
    {
        return  $this->belongsTo(Stock::class, 'stock_id');
    }
}
