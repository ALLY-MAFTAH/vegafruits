<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'date',
        'number',
        'status',
        'served_by',
        'served_date',
        'customer_id',
        'total_amount',
        'delivery_date',
        'delivery_time',
        'delivery_location',
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function items()
    {
        return  $this->hasMany(Item::class);
    }
    public function customer()
    {
        return  $this->belongsTo(Customer::class, 'customer_id');
    }
}
