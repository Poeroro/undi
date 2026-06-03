<?php

namespace App\Filament\Resources\GiftAccountResource\Pages;

use App\Filament\Resources\GiftAccountResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGiftAccount extends EditRecord
{
    protected static string $resource = GiftAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
