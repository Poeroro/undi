<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GiftAccountResource\Pages;
use App\Models\GiftAccount;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GiftAccountResource extends Resource
{
    protected static ?string $model = GiftAccount::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Undangan';

    protected static ?string $navigationLabel = 'Amplop Digital';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('invitation_id')->relationship('invitation', 'title')->searchable()->required(),
            Select::make('type')->options(['bank' => 'Bank', 'e_wallet' => 'E-wallet', 'qris' => 'QRIS'])->required(),
            TextInput::make('provider_name')->maxLength(100),
            TextInput::make('account_holder')->maxLength(140),
            TextInput::make('account_number')->maxLength(100),
            FileUpload::make('qris_path')->image()->directory('invitations/qris')->visibility('public'),
            Textarea::make('instructions')->columnSpanFull(),
            Toggle::make('is_visible')->default(true),
            TextInput::make('sort_order')->numeric()->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invitation.title')->searchable(),
                TextColumn::make('type')->badge(),
                TextColumn::make('provider_name')->searchable(),
                TextColumn::make('account_holder')->searchable(),
                TextColumn::make('is_visible')->badge()->formatStateUsing(fn (bool $state) => $state ? 'Tampil' : 'Sembunyi'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')->options(['bank' => 'Bank', 'e_wallet' => 'E-wallet', 'qris' => 'QRIS']),
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
            'index' => Pages\ListGiftAccounts::route('/'),
            'create' => Pages\CreateGiftAccount::route('/create'),
            'edit' => Pages\EditGiftAccount::route('/{record}/edit'),
        ];
    }
}
