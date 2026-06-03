<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvitationTemplateResource\Pages;
use App\Models\InvitationTemplate;
use Filament\Actions;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InvitationTemplateResource extends Resource
{
    protected static ?string $model = InvitationTemplate::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Konten';

    protected static ?string $navigationLabel = 'Template Undangan';

    protected static ?string $modelLabel = 'Template Undangan';

    protected static ?string $pluralModelLabel = 'Template Undangan';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required()->maxLength(120),
            TextInput::make('slug')->required()->unique(ignoreRecord: true)->maxLength(140),
            Select::make('category')->options(config('undi.event_types'))->required(),
            Textarea::make('description')->columnSpanFull(),
            FileUpload::make('preview_image_path')->image()->directory('templates')->visibility('public'),
            Select::make('view_path')
                ->options([
                    'invitations.templates.elegant' => 'Undangan publik responsif',
                ])
                ->required()
                ->default('invitations.templates.elegant'),
            ColorPicker::make('default_theme.color')
                ->label('Warna default')
                ->default('#a4785b'),
            Select::make('default_theme.font')
                ->label('Font default')
                ->options(collect(config('undi.theme_fonts'))->mapWithKeys(fn (array $font, string $value): array => [$value => $font['label']])->all())
                ->default('Georgia'),
            Toggle::make('is_premium'),
            Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('preview_image_path')->label('Preview'),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('category')->badge(),
                TextColumn::make('invitations_count')->counts('invitations')->label('Dipakai'),
                TextColumn::make('is_premium')->badge()->formatStateUsing(fn (bool $state) => $state ? 'Premium' : 'Free'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')->options(config('undi.event_types')),
                Tables\Filters\TernaryFilter::make('is_active'),
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
            'index' => Pages\ListInvitationTemplates::route('/'),
            'create' => Pages\CreateInvitationTemplate::route('/create'),
            'edit' => Pages\EditInvitationTemplate::route('/{record}/edit'),
        ];
    }
}
