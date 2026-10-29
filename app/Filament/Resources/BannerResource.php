<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Models\Banner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Marketing';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Hero content')->schema([
                    Forms\Components\TextInput::make('title')->required()->maxLength(255),
                    Forms\Components\TextInput::make('subtitle')->maxLength(255),
                    Forms\Components\FileUpload::make('image')
                        ->image()
                        ->directory('banners')
                        ->disk('public')
                        ->helperText('Recommended: 1920×1080'),
                    Forms\Components\FileUpload::make('video')
                        ->directory('banners/videos')
                        ->disk('public')
                        ->acceptedFileTypes(['video/mp4', 'video/webm'])
                        ->helperText('Optional — overrides image when set'),
                    Forms\Components\TextInput::make('link')->url()->placeholder('/shop'),
                    Forms\Components\TextInput::make('button_text')->default('Shop Now'),
                ]),
                Forms\Components\Section::make('Settings')->columns(3)->schema([
                    Forms\Components\Select::make('placement')
                        ->options(['hero' => 'Homepage Hero', 'promo' => 'Promo Strip'])
                        ->default('hero')
                        ->required(),
                    Forms\Components\Toggle::make('is_active')->default(true),
                    Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')->disk('public')->height(48),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('placement')->badge(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}
