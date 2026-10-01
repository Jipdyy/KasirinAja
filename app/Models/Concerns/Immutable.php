<?php

namespace App\Models\Concerns;


/**
 * @method static void updating(\Closure $callback)
 * @method static void deleting(\Closure $callback)
 */
trait Immutable
{
    protected static function bootImmutable(): void
    {
        static::updating(function ($model) {
            throw new \RuntimeException(
                class_basename($model) . ' tidak boleh diubah.'
            );
        });

        static::deleting(function ($model) {
            throw new \RuntimeException(
                class_basename($model) . ' tidak boleh dihapus.'
            );
        });
    }
}