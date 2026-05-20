<?php

namespace App\Models;

use Database\Factories\PlansaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Plansa extends Model
{
    /** @use HasFactory<PlansaFactory> */
    use HasFactory;

    protected $table = 'planse';

    protected $fillable = [
        'uuid',
        'title',
        'file_path',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $plansa): void {
            if (empty($plansa->uuid)) {
                $plansa->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function publicUrl(): string
    {
        return route('plansa.show', $this->uuid);
    }
}
