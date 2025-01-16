<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['user_id','total_amount','status','payment_status','user_id'];
    protected $casts = [
        'total_amount' => 'float',
    ];

    public function products():BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_products')->withPivot('quantity', 'price');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
