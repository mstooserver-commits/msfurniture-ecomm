<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    protected static ?string $title = 'Product Images';

    protected static ?string $icon = 'heroicon-o-photo';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\FileUpload::make('path')
                ->label('Image')
                ->image()
                ->disk('public')
                ->directory('products')
                ->visibility('public')
                ->imageEditor()
                ->helperText('Shown on the product card and product detail page. Recommended: 1200 × 1500px.')
                ->required()
                ->columnSpanFull(),
            Forms\Components\TextInput::make('alt')
                ->label('Alt text')
                ->maxLength(255),
            Forms\Components\Toggle::make('is_primary')
                ->label('Primary image')
                ->default(false),
            Forms\Components\TextInput::make('sort_order')
                ->numeric()
                ->default(0)
                ->minValue(0),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('path')
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                Tables\Columns\ImageColumn::make('path')
                    ->disk('public')
                    ->height(80),
                Tables\Columns\TextColumn::make('alt'),
                Tables\Columns\IconColumn::make('is_primary')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
