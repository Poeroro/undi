<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'template_id',
        'title',
        'slug',
        'event_type',
        'primary_name',
        'secondary_name',
        'host_name',
        'cover_photo_path',
        'profile_photo_path',
        'event_date',
        'event_time',
        'timezone',
        'venue_name',
        'venue_address',
        'maps_url',
        'maps_embed_url',
        'description',
        'status',
        'password',
        'music_path',
        'music_enabled',
        'theme_color',
        'theme_font',
        'youtube_url',
        'share_message_template',
        'subdomain',
        'custom_domain',
        'domain_status',
        'view_count',
        'share_count',
        'last_viewed_at',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'music_enabled' => 'boolean',
            'view_count' => 'integer',
            'share_count' => 'integer',
            'last_viewed_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(InvitationTemplate::class, 'template_id');
    }

    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class);
    }

    public function rsvps(): HasMany
    {
        return $this->hasMany(Rsvp::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(GuestMessage::class);
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class)->orderBy('sort_order');
    }

    public function stories(): HasMany
    {
        return $this->hasMany(Story::class)->orderBy('sort_order');
    }

    public function giftAccounts(): HasMany
    {
        return $this->hasMany(GiftAccount::class)->orderBy('sort_order');
    }

    public function views(): HasMany
    {
        return $this->hasMany(InvitationView::class);
    }

    public function publicUrl(?Guest $guest = null): string
    {
        $parameters = ['slug' => $this->slug];

        if ($guest) {
            $parameters['guest'] = $guest->personal_token;
            $parameters['to'] = $guest->name;
        }

        return URL::route('invitations.show', $parameters);
    }

    public function qrPngUrl(): string
    {
        return 'https://api.qrserver.com/v1/create-qr-code/?size=640x640&format=png&data='.urlencode($this->publicUrl());
    }

    public function shareMessageFor(Guest|string|null $guest = null): string
    {
        $guestName = $guest instanceof Guest ? $guest->name : ($guest ?: 'Bapak/Ibu/Saudara/i');
        $template = $this->share_message_template ?: config('undi.share_message');

        return strtr($template, [
            '{nama_tamu}' => $guestName,
            '{judul_undangan}' => $this->title,
            '{tanggal}' => optional($this->event_date)->translatedFormat('d F Y') ?: '-',
            '{lokasi}' => $this->venue_name ?: $this->venue_address ?: '-',
            '{link_undangan}' => $this->publicUrl($guest instanceof Guest ? $guest : null),
        ]);
    }

    public function eventDateTime(): ?Carbon
    {
        if (! $this->event_date) {
            return null;
        }

        return Carbon::parse($this->event_date->format('Y-m-d').' '.($this->event_time ?: '00:00:00'), $this->timezone ?: config('app.timezone'));
    }
}
