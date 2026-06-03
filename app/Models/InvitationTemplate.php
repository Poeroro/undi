<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvitationTemplate extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'preview_image_path',
        'view_path',
        'default_theme',
        'is_premium',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'default_theme' => 'array',
            'is_premium' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class, 'template_id');
    }
}
