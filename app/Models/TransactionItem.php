<?php

namespace App\Models;

use App\Models\Concerns\Immutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionItem extends Model
{
    use Immutable;
    
    public $timestamps = false;

    protected $fillable = [
        'transaction_id', 'product_id', 'product_name', 'price', 'qty', 'subtotal',
    ];

    protected $casts = [
        'price' => 'integer',
        'qty' => 'integer',
        'subtotal' => 'integer',
    ];

    public function product(): BelongsTo{
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function transaction(): BelongsTo{
        return $this->belongsTo(Transaction::class);
    }

}
