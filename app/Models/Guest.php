<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'invitation_id',
        'name',
        'whatsapp',
        'email',
        'category',
        'max_companions',
        'personal_token',
        'status',
        'invitation_sent_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'invitation_sent_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Guest $guest): void {
            $guest->personal_token ??= Str::random(32);
        });
    }

    public function invitation(): BelongsTo
    {
        return $this->belongsTo(Invitation::class);
    }

    public function rsvps(): HasMany
    {
        return $this->hasMany(Rsvp::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(GuestMessage::class);
    }
}
