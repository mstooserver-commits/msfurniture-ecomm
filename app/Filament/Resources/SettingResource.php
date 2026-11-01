<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'Store settings';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Storefront setting')->description('Use the keys below for the announcement bar, footer contact details and social links.')->schema([
                Forms\Components\Select::make('group')->options(['general' => 'General', 'footer' => 'Footer', 'social' => 'Social'])->default('general')->required(),
                Forms\Components\TextInput::make('key')->required()->unique(ignoreRecord: true)->helperText('Examples: announcement, footer_story, footer_email, footer_phone, footer_address, instagram_url, facebook_url, pinterest_url'),
                Forms\Components\Select::make('type')->options(['string' => 'Text', 'boolean' => 'Yes / no', 'integer' => 'Number', 'json' => 'JSON'])->default('string')->required(),
                Forms\Components\Textarea::make('value')->rows(4)->columnSpanFull(),
            ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('group')->badge()->sortable(),
            Tables\Columns\TextColumn::make('key')->searchable()->copyable(),
            Tables\Columns\TextColumn::make('value')->limit(70)->wrap(),
            Tables\Columns\TextColumn::make('updated_at')->since()->label('Updated'),
        ])->defaultSort('group')->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListSettings::route('/'), 'create' => Pages\CreateSetting::route('/create'), 'edit' => Pages\EditSetting::route('/{record}/edit')];
    }
}
