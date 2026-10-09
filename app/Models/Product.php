<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'name', 'price', 'stock', 'image', 'is_active',
    ];

    protected $casts = [
        'price' => 'integer',
        'stock' => 'integer',
        'is_active' => 'boolean',
    ];

    //relation
    public function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }

    public function transactionItems(): HasMany {
        return $this->hasMany(TransactionItem::class);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $keyword
     * @return \Illuminate\Database\Eloquent\Builder
    */
    public function scopeSearch(Builder $query, ?string $keyword): Builder{
        return $query->when($keyword, function($query, $keyword) {
            $query->where("name", "like", "%{$keyword}%");
        });
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
    */
    public function scopeInCategory(Builder $query, ?string $categoryId): Builder {
        return $query->when($categoryId, function($query, $categoryId){
            $query->where('category_id', $categoryId);
        });
    }

    public function isSellable(): bool
    {
        return $this->is_active && $this->stock > 0;
    }

    public function scopeSellAble(Builder $query): Builder{
        return $query->where('is_active', true)
                    ->where('stock', '>', 0);
    }

    public function scopeSoldOut(Builder $query): Builder{
        return $query->where(function (Builder $q) {
            $q->where('is_active', false)
            ->orWhere('stock', '<=', 0);
        });
    }

    public function scopeStatusIs(Builder $query, ?string $status): Builder {
        return match ($status) {
            'Tersedia' => $query->sellAble(),
            'Habis'    => $query->soldOut(),
            default    => $query,
        };
    }

    protected function statusLabel(): Attribute {
        return Attribute::make(
            get: fn () => $this->isSellable() ? 'Tersedia' : 'Habis'
        );
    }

    protected function formattedPrice(): Attribute {
        return Attribute::make(
            get: fn () => 'Rp. ' . number_format($this->price, 0, ',', '.')
        );
    }

    protected function imageUrl(): Attribute {
        return Attribute::make(
            get: function() {
                if ($this->image && Storage::disk('public')->exists($this->image)) {
                    return Storage::url($this->image);
                }

                return asset('images/placeholderFood.jpg');
            }
        );
    }
}
