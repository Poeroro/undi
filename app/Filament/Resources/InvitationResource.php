<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvitationResource\Pages;
use App\Models\Invitation;
use App\Models\InvitationTemplate;
use Filament\Actions;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InvitationResource extends Resource
{
    protected static ?string $model = Invitation::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Undangan';

    protected static ?string $navigationLabel = 'Undangan';

    protected static ?string $modelLabel = 'Undangan';

    protected static ?string $pluralModelLabel = 'Undangan';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('user_id')->relationship('user', 'name')->searchable()->required(),
            Select::make('template_id')
                ->relationship('template', 'name')
                ->searchable()
                ->live()
                ->afterStateUpdated(function (Set $set, mixed $state): void {
                    $template = InvitationTemplate::query()->find($state);

                    if (! $template) {
                        return;
                    }

                    $theme = $template->default_theme ?: data_get(config('undi.template_skins'), "{$template->slug}.default_theme", []);

                    $set('theme_color', $theme['color'] ?? '#a4785b');
                    $set('theme_font', array_key_exists($theme['font'] ?? '', config('undi.theme_fonts')) ? $theme['font'] : 'Georgia');
                }),
            TextInput::make('title')->required()->maxLength(160),
            TextInput::make('slug')->required()->unique(ignoreRecord: true)->maxLength(180),
            Select::make('event_type')->options(config('undi.event_types'))->required(),
            TextInput::make('primary_name')->required(),
            TextInput::make('secondary_name'),
            TextInput::make('host_name'),
            DatePicker::make('event_date')->required(),
            TimePicker::make('event_time')->seconds(false),
            TextInput::make('timezone')->default('Asia/Jakarta'),
            TextInput::make('venue_name'),
            Textarea::make('venue_address')->columnSpanFull(),
            TextInput::make('maps_url')->url(),
            TextInput::make('maps_embed_url')->url(),
            Textarea::make('description')->columnSpanFull(),
            Select::make('status')->options(['draft' => 'Draft', 'active' => 'Active', 'paused' => 'Paused', 'expired' => 'Expired'])->required(),
            FileUpload::make('cover_photo_path')->image()->directory('invitations/covers')->visibility('public'),
            FileUpload::make('profile_photo_path')->image()->directory('invitations/profiles')->visibility('public'),
            FileUpload::make('music_path')->acceptedFileTypes(['audio/mpeg', 'audio/wav'])->directory('invitations/music')->visibility('public'),
            Toggle::make('music_enabled'),
            ColorPicker::make('theme_color')->default('#a4785b'),
            Select::make('theme_font')
                ->options(collect(config('undi.theme_fonts'))->mapWithKeys(fn (array $font, string $value): array => [$value => $font['label']])->all())
                ->default('Georgia')
                ->required(),
            TextInput::make('youtube_url')->url(),
            Textarea::make('share_message_template')->columnSpanFull(),
            TextInput::make('subdomain'),
            TextInput::make('custom_domain'),
            Select::make('domain_status')->options(['not_configured' => 'Not configured', 'pending' => 'Pending', 'verified' => 'Verified', 'failed' => 'Failed'])->default('not_configured'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('user.name')->searchable(),
                TextColumn::make('event_type')->badge(),
                TextColumn::make('status')->badge(),
                TextColumn::make('event_date')->date()->sortable(),
                TextColumn::make('view_count')->numeric()->sortable(),
                TextColumn::make('share_count')->numeric()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(['draft' => 'Draft', 'active' => 'Active', 'paused' => 'Paused', 'expired' => 'Expired']),
                Tables\Filters\SelectFilter::make('event_type')->options(config('undi.event_types')),
            ])
            ->recordActions([
                Actions\Action::make('preview')
                    ->url(fn (Invitation $record) => $record->publicUrl())
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
            'index' => Pages\ListInvitations::route('/'),
            'create' => Pages\CreateInvitation::route('/create'),
            'edit' => Pages\EditInvitation::route('/{record}/edit'),
        ];
    }
}
