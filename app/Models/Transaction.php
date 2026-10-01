<?php

namespace App\Models;

use App\Models\Concerns\Immutable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Transaction extends Model
{
    use Immutable;

    public const UPDATED_AT = null;

    protected $fillable = [
        'invoice_code', 'user_id', 'total_items', 'total_amount', /*'payment_method'*/ 'cash_amount', 'change_amount', 'status'
    ];

    protected $casts = [
        'total_items' => 'integer',
        'total_amount' => 'integer',
        'cash_amount' => 'integer',
        'change_amount' => 'integer',
    ];

    //relation
    public function cashier(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id') 
                    ->withTrashed();
    }

    public function items(): HasMany {
        return $this->hasMany(TransactionItem::class);
    }

    //acessor
    protected function paymentMethodLabel(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn () => match ($this->payment_method) {
                'tunai' => 'Tunai',
                'qris'  => 'QRIS',
                default => ucfirst($this->payment_method ?? '-'),
            },
        );
    }

    protected function formattedTotalAmount(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn () => 'Rp ' . number_format($this->total_amount, 0, ',', '.'),
        );
    }

    //scope
    public function scopeOwnedBy(Builder $query, int $user_id): Builder {
        return $query->where('user_id', $user_id);
    }

    public function scopeDateBetween(Builder $query, $start, $end): Builder {
        return $query->whereBetween('created_at', [
            $start . ' 00:00:00',
            $end . ' 23:59:59'
        ]);
    }

    public function scopeSearchInvoice(Builder $query, ?string $keyword): Builder {
        return $query->when($keyword, function($q) use ($keyword) {
            $q->where('invoice_code', 'like', "%{$keyword}%");
        });
    }

    public function scopeByCashier(Builder $query, ?string $name): Builder {
        return $query->when($name, function($q) use ($name){
            $q->whereHas('cashier', function($qCashier) use ($name){
                $qCashier->where('name', 'like', "%{$name}%");
            });
        });
    }
}


