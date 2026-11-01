<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->uppercase()
                    ->maxLength(50),
                Forms\Components\Select::make('type')
                    ->options([
                        'percentage' => 'Percentage (%)',
                        'fixed' => 'Fixed amount ($)',
                    ])
                    ->required()
                    ->native(false),
                Forms\Components\TextInput::make('value')
                    ->required()
                    ->numeric()
                    ->minValue(0),
                Forms\Components\TextInput::make('min_order_amount')
                    ->label('Minimum order')
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\TextInput::make('usage_limit')
                    ->numeric()
                    ->helperText('Leave empty for unlimited'),
                Forms\Components\TextInput::make('used_count')
                    ->disabled()
                    ->default(0),
                Forms\Components\DateTimePicker::make('starts_at'),
                Forms\Components\DateTimePicker::make('expires_at'),
                Forms\Components\Toggle::make('is_active')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')->searchable()->copyable()->weight('bold'),
                Tables\Columns\TextColumn::make('type')->badge(),
                Tables\Columns\TextColumn::make('value')
                    ->formatStateUsing(fn ($state, Coupon $record) => $record->type === 'percentage' ? "{$state}%" : '₹'.number_format($state, 2)),
                Tables\Columns\TextColumn::make('used_count')->label('Used'),
                Tables\Columns\TextColumn::make('expires_at')->dateTime()->placeholder('No expiry'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
