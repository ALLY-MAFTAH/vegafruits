<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'mobile',

    ];

    protected $dates = [
        'deleted_at'
    ];

    public function goods()
    {
        return  $this->hasMany(Good::class);
    }
    public function orders()
    {
        return  $this->hasMany(Order::class);
    }
    public function sales()
    {
        return  $this->hasMany(Sale::class);
    }
    public function messages()
    {
        return  $this->hasMany(Message::class);
    }
}
