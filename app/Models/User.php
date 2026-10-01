<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;


class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    public const SUPER_ADMIN_ROLE = 'Super Admin';

    protected $fillable = ['name', 'email', 'password', 'is_active'];
    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function transactions(): HasMany {
        return $this->hasMany(Transaction::class);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
    */
    public function scopeActive(Builder $query): Builder {
        return $query->where('is_active', true);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $keyword
     * @return \Illuminate\Database\Eloquent\Builder
    */
    public function scopeSearch(Builder $query,?string $keyword): Builder {
        return $query->when($keyword, function($q, $keyword) {
            $q->where(function ($subQuery) use ($keyword) {
                $subQuery->where('name', 'like', "%{$keyword}%")
                         ->orWhere('email', 'like', "%{$keyword}%");
            });
        });
    }

    public function scopeWithRole(Builder $query, ?string $role): Builder {
        return $query->when($role, function ($q, $role) {
            $q->role($role);
        });
    }

    public function isSuperAdmin(): bool {
        return $this->hasRole(self::SUPER_ADMIN_ROLE);
    }

    public function deactivate(): void {
        DB::transaction(function(){
            $this->update([
                'is_active' => false,
            ]);
    
            $this->delete();
        });
    }
}
