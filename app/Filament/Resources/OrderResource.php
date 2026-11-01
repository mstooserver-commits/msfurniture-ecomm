<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\ItemsRelationManager;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'order_number';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Order details')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->disabled(),
                        Forms\Components\Select::make('status')
                            ->options(collect(OrderStatus::cases())->mapWithKeys(
                                fn (OrderStatus $s) => [$s->value => $s->label()]
                            ))
                            ->required()
                            ->native(false),
                        Forms\Components\Select::make('payment_status')
                            ->options(collect(PaymentStatus::cases())->mapWithKeys(
                                fn (PaymentStatus $s) => [$s->value => $s->label()]
                            ))
                            ->required()
                            ->native(false),
                        Forms\Components\TextInput::make('payment_method')
                            ->disabled(),
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->disabled(),
                        Forms\Components\TextInput::make('guest_email')
                            ->email()
                            ->disabled(),
                    ]),
                Forms\Components\Section::make('Totals')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('subtotal')->prefix('$')->disabled(),
                        Forms\Components\TextInput::make('discount_amount')->prefix('$')->disabled(),
                        Forms\Components\TextInput::make('tax_amount')->prefix('$')->disabled(),
                        Forms\Components\TextInput::make('shipping_amount')->prefix('$')->disabled(),
                        Forms\Components\TextInput::make('wallet_used')->prefix('$')->disabled(),
                        Forms\Components\TextInput::make('total')->prefix('$')->disabled(),
                        Forms\Components\TextInput::make('coupon_code')->disabled(),
                    ]),
                Forms\Components\Section::make('Shipping & tracking')
                    ->schema([
                        Forms\Components\KeyValue::make('shipping_address')
                            ->disabled(),
                        Forms\Components\TextInput::make('tracking_number'),
                        Forms\Components\TextInput::make('tracking_url')
                            ->url(),
                        Forms\Components\Textarea::make('notes')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->placeholder('Guest')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (OrderStatus $state): string => $state->color()),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn (PaymentStatus $state): string => match ($state) {
                        PaymentStatus::Paid => 'success',
                        PaymentStatus::Pending => 'warning',
                        PaymentStatus::Failed => 'danger',
                        PaymentStatus::Refunded => 'gray',
                    }),
                Tables\Columns\TextColumn::make('total')
                    ->money('INR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Items'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(collect(OrderStatus::cases())->mapWithKeys(
                        fn (OrderStatus $s) => [$s->value => $s->label()]
                    )),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options(collect(PaymentStatus::cases())->mapWithKeys(
                        fn (PaymentStatus $s) => [$s->value => $s->label()]
                    )),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('invoice')
                    ->label('Invoice')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(fn (Order $record): string => route('orders.invoice', $record))
                    ->openUrlInNewTab(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
