<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    protected $fillable = ['name', 'description'];

    //relation
    public function products(): HasMany {
        return $this->hasMany(Product::class);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $keyword
     * @return \Illuminate\Database\Eloquent\Builder
    */
    public function scopeSearch(Builder $query, ?string $keyword): Builder {
        return $query->when($keyword, function ($q, $keyword) {
            $q->where("name", "like", "%{$keyword}%");
        });
    }

    public function canBeDeleted(): bool {
        return $this->products()->withTrashed()->count() === 0;
    }
}
