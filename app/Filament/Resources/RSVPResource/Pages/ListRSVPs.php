<?php

namespace App\Filament\Resources\RSVPResource\Pages;

use App\Filament\Resources\RSVPResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRSVPs extends ListRecords
{
    protected static string $resource = RSVPResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
