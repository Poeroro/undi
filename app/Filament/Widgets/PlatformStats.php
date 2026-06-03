<?php

namespace App\Filament\Widgets;

use App\Models\Invitation;
use App\Models\InvitationTemplate;
use App\Models\Transaction;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PlatformStats extends StatsOverviewWidget
{
    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        return [
            Stat::make('Total user', User::query()->count()),
            Stat::make('Undangan aktif', Invitation::query()->where('status', 'active')->count()),
            Stat::make('Template', InvitationTemplate::query()->count()),
            Stat::make('Transaksi paid', Transaction::query()->where('status', 'paid')->count()),
        ];
    }
}
