<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait HasUserActions
{
    public static function bootHasUserActions(): void
    {
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
                $model->updated_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });

        static::deleting(function ($model) {
            if (Auth::check() && $model->usesSoftDeletes()) {
                // Только если модель использует SoftDeletes
                $model->deleted_by = Auth::id();
                $model->saveQuietly(); // чтобы не вызвать событие обновления снова
            }
        });

        static::restoring(function ($model) {
            if (Auth::check()) {
                $model->deleted_by = null;
                $model->updated_by = Auth::id();
            }
        });
    }

    /**
     * Проверяем, использует ли модель SoftDeletes.
     */
    protected function usesSoftDeletes(): bool
    {
        return in_array('Illuminate\\Database\\Eloquent\\SoftDeletes', class_uses_recursive(static::class));
    }
}
