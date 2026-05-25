<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        "name",
        "price",
        "description",
        "status"
    ];

    // STATUS JANGAN BOOLEAN
    protected function casts(): array
    {
        return [
            "price" => "integer",
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}