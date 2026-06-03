<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanResource\Pages;
use App\Models\Plan;
use Filament\Actions;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Paket & Billing';

    protected static ?string $navigationLabel = 'Paket';

    protected static ?string $modelLabel = 'Paket';

    protected static ?string $pluralModelLabel = 'Paket';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required()->maxLength(80),
            TextInput::make('slug')->required()->unique(ignoreRecord: true)->maxLength(100),
            Textarea::make('description')->columnSpanFull(),
            TextInput::make('price')->numeric()->prefix('Rp')->required(),
            TextInput::make('invitation_limit')->numeric()->required(),
            TextInput::make('guest_limit')->numeric()->required(),
            TextInput::make('gallery_limit')->numeric()->required(),
            TextInput::make('active_days')->numeric()->required(),
            Toggle::make('custom_music'),
            Toggle::make('qr_code'),
            Toggle::make('rsvp'),
            Toggle::make('custom_domain'),
            Toggle::make('is_featured'),
            Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('price')->money('IDR')->sortable(),
                TextColumn::make('invitation_limit')->label('Undangan'),
                TextColumn::make('guest_limit')->label('Tamu'),
                IconColumn::make('custom_domain')->boolean(),
                IconColumn::make('is_active')->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
                Tables\Filters\TernaryFilter::make('custom_domain'),
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
            'index' => Pages\ListPlans::route('/'),
            'create' => Pages\CreatePlan::route('/create'),
            'edit' => Pages\EditPlan::route('/{record}/edit'),
        ];
    }
}
