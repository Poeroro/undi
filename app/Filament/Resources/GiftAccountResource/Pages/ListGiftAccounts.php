<?php

namespace App\Filament\Resources\GiftAccountResource\Pages;

use App\Filament\Resources\GiftAccountResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGiftAccounts extends ListRecords
{
    protected static string $resource = GiftAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
