<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
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

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Sistem';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('group')->required()->maxLength(80),
            TextInput::make('key')->required()->maxLength(120),
            Select::make('type')->options(['string' => 'String', 'boolean' => 'Boolean', 'integer' => 'Integer', 'json' => 'JSON'])->required(),
            Textarea::make('value')->columnSpanFull(),
            Toggle::make('is_public'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('group')->badge()->searchable(),
                TextColumn::make('key')->searchable()->sortable(),
                TextColumn::make('type')->badge(),
                TextColumn::make('value')->limit(60),
                TextColumn::make('is_public')->badge()->formatStateUsing(fn (bool $state) => $state ? 'Public' : 'Private'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group')->options(['general' => 'General', 'support' => 'Support', 'payment' => 'Payment', 'mail' => 'Mail', 'seo' => 'SEO']),
                Tables\Filters\TernaryFilter::make('is_public'),
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
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
