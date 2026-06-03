<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuestResource\Pages;
use App\Models\Guest;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GuestResource extends Resource
{
    protected static ?string $model = Guest::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Undangan';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('invitation_id')->relationship('invitation', 'title')->searchable()->required(),
            TextInput::make('name')->required()->maxLength(120),
            TextInput::make('whatsapp')->tel()->maxLength(40),
            TextInput::make('email')->email()->maxLength(160),
            Select::make('category')->options(config('undi.guest_categories'))->required(),
            TextInput::make('max_companions')->numeric()->required()->minValue(1)->maxValue(20),
            Select::make('status')->options(['draft' => 'Draft', 'sent' => 'Sent', 'opened' => 'Opened'])->required(),
            Textarea::make('notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('invitation.title')->searchable(),
                TextColumn::make('category')->badge(),
                TextColumn::make('whatsapp')->searchable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')->options(config('undi.guest_categories')),
                Tables\Filters\SelectFilter::make('status')->options(['draft' => 'Draft', 'sent' => 'Sent', 'opened' => 'Opened']),
            ])
            ->recordActions([
                Actions\Action::make('copy_link')
                    ->label('Preview')
                    ->url(fn (Guest $record) => $record->invitation->publicUrl($record))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListGuests::route('/'),
            'create' => Pages\CreateGuest::route('/create'),
            'edit' => Pages\EditGuest::route('/{record}/edit'),
        ];
    }
}
