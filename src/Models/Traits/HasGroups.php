<?php

namespace Redsnapper\LaravelDoorman\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Redsnapper\LaravelDoorman\PermissionsRegistrar;

trait HasGroups
{
    public function groupKeyName(): string
    {
        return app(PermissionsRegistrar::class)->getGroupClass()->getKeyName();
    }

    public function groupPivotKeyName(): string
    {
        return app(PermissionsRegistrar::class)->getGroupClass()->getPivotKeyName();
    }

    /**
     * @return BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(app(PermissionsRegistrar::class)->getGroupClass())
            ->withPivot(
                ['is_current']
            );
    }

    public function scopeForGroup(Builder $query, Collection $groups)
    {
        $query->whereHas('groups', function ($subQuery) use ($groups) {
            $subQuery->whereIn($this->groupPivotKeyName(), $groups->pluck($this->groupKeyName()));
        });
    }
}