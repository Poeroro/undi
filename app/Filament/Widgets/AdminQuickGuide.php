<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\GuestMessageResource;
use App\Filament\Resources\GuestResource;
use App\Filament\Resources\InvitationResource;
use App\Filament\Resources\InvitationTemplateResource;
use App\Filament\Resources\PlanResource;
use App\Filament\Resources\RSVPResource;
use App\Filament\Resources\SettingResource;
use App\Filament\Resources\TransactionResource;
use App\Filament\Resources\UserResource;
use Filament\Widgets\Widget;

class AdminQuickGuide extends Widget
{
    protected string $view = 'filament.widgets.admin-quick-guide';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = -3;

    protected static bool $isLazy = false;

    protected function getViewData(): array
    {
        return [
            'actions' => [
                [
                    'title' => 'Template Undangan',
                    'description' => 'Atur katalog desain, preview, default warna, dan default font.',
                    'url' => InvitationTemplateResource::getUrl(),
                ],
                [
                    'title' => 'Undangan',
                    'description' => 'Pantau undangan user, status publikasi, statistik view, dan preview.',
                    'url' => InvitationResource::getUrl(),
                ],
                [
                    'title' => 'Tamu & RSVP',
                    'description' => 'Lihat daftar tamu, link personal, dan konfirmasi kehadiran.',
                    'url' => GuestResource::getUrl(),
                    'secondaryUrl' => RSVPResource::getUrl(),
                    'secondaryLabel' => 'Buka RSVP',
                ],
                [
                    'title' => 'Ucapan',
                    'description' => 'Moderasi ucapan sebelum tampil di halaman undangan publik.',
                    'url' => GuestMessageResource::getUrl(),
                ],
                [
                    'title' => 'Paket & Transaksi',
                    'description' => 'Kelola limit paket, harga, langganan, dan pembayaran.',
                    'url' => PlanResource::getUrl(),
                    'secondaryUrl' => TransactionResource::getUrl(),
                    'secondaryLabel' => 'Buka Transaksi',
                ],
                [
                    'title' => 'User & Setting',
                    'description' => 'Kelola akun, role, metadata SEO, support, dan konfigurasi situs.',
                    'url' => UserResource::getUrl(),
                    'secondaryUrl' => SettingResource::getUrl(),
                    'secondaryLabel' => 'Buka Setting',
                ],
            ],
        ];
    }
}
