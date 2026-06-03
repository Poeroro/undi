<?php

namespace App\Filament\Resources\RSVPResource\Pages;

use App\Filament\Resources\RSVPResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRSVP extends EditRecord
{
    protected static string $resource = RSVPResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
