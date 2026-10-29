<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';

    protected static ?string $title = 'Variants (Size / Color)';

    protected static ?string $icon = 'heroicon-o-squares-2x2';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('sku')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\TextInput::make('size')
                    ->placeholder('S, M, L, XL'),
                Forms\Components\TextInput::make('color')
                    ->placeholder('Black'),
            ]),
            Forms\Components\ColorPicker::make('color_hex'),
            Forms\Components\TextInput::make('price')
                ->numeric()
                ->prefix('$')
                ->helperText('Leave empty to use product base price'),
            Forms\Components\TextInput::make('stock')
                ->numeric()
                ->required()
                ->default(0)
                ->minValue(0),
            Forms\Components\Toggle::make('is_active')
                ->default(true),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sku')->searchable(),
                Tables\Columns\TextColumn::make('size'),
                Tables\Columns\TextColumn::make('color'),
                Tables\Columns\ColorColumn::make('color_hex'),
                Tables\Columns\TextColumn::make('price')->money('AUD'),
                Tables\Columns\TextColumn::make('stock')
                    ->badge()
                    ->color(fn (int $state): string => $state <= 0 ? 'danger' : ($state <= 5 ? 'warning' : 'success')),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
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
