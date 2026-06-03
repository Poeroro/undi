<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RSVPResource\Pages;
use App\Models\Rsvp;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RSVPResource extends Resource
{
    protected static ?string $model = Rsvp::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Undangan';

    protected static ?string $navigationLabel = 'RSVP';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('invitation_id')->relationship('invitation', 'title')->searchable()->required(),
            Select::make('guest_id')->relationship('guest', 'name')->searchable(),
            TextInput::make('name')->required()->maxLength(120),
            Select::make('attendance')->options(['attending' => 'Hadir', 'declined' => 'Tidak hadir', 'maybe' => 'Masih ragu'])->required(),
            TextInput::make('guests_count')->numeric()->required()->minValue(1),
            Textarea::make('notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('invitation.title')->searchable(),
                TextColumn::make('attendance')->badge(),
                TextColumn::make('guests_count')->numeric()->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('attendance')->options(['attending' => 'Hadir', 'declined' => 'Tidak hadir', 'maybe' => 'Masih ragu']),
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRSVPs::route('/'),
            'create' => Pages\CreateRSVP::route('/create'),
            'edit' => Pages\EditRSVP::route('/{record}/edit'),
        ];
    }
}
