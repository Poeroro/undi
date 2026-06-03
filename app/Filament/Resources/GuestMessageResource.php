<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuestMessageResource\Pages;
use App\Models\GuestMessage;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GuestMessageResource extends Resource
{
    protected static ?string $model = GuestMessage::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Undangan';

    protected static ?string $navigationLabel = 'Ucapan';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('invitation_id')->relationship('invitation', 'title')->searchable()->required(),
            Select::make('guest_id')->relationship('guest', 'name')->searchable(),
            TextInput::make('name')->required()->maxLength(120),
            Textarea::make('message')->required()->columnSpanFull(),
            Toggle::make('is_visible')->label('Tampilkan'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('invitation.title')->searchable(),
                TextColumn::make('message')->limit(60),
                TextColumn::make('is_visible')->badge()->formatStateUsing(fn (bool $state) => $state ? 'Tampil' : 'Disembunyikan'),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_visible'),
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
            'index' => Pages\ListGuestMessages::route('/'),
            'create' => Pages\CreateGuestMessage::route('/create'),
            'edit' => Pages\EditGuestMessage::route('/{record}/edit'),
        ];
    }
}
