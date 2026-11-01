<?php
namespace App\Filament\Resources;
use App\Filament\Resources\ReviewResource\Pages; use App\Models\Review; use Filament\Forms; use Filament\Forms\Form; use Filament\Resources\Resource; use Filament\Tables; use Filament\Tables\Table;
class ReviewResource extends Resource {
 protected static ?string $model=Review::class; protected static ?string $navigationIcon='heroicon-o-star'; protected static ?string $navigationGroup='Sales';
 public static function form(Form $form):Form{return $form->schema([Forms\Components\Select::make('product_id')->relationship('product','name')->searchable()->preload()->required(),Forms\Components\Select::make('user_id')->relationship('user','name')->searchable()->preload()->required(),Forms\Components\Select::make('rating')->options(array_combine(range(1,5),range(1,5)))->required(),Forms\Components\Toggle::make('is_approved'),Forms\Components\TextInput::make('title')->columnSpanFull(),Forms\Components\Textarea::make('body')->rows(5)->columnSpanFull()])->columns(2);}
 public static function table(Table $table):Table{return $table->columns([Tables\Columns\TextColumn::make('product.name')->searchable()->limit(30),Tables\Columns\TextColumn::make('user.name')->label('Customer')->searchable(),Tables\Columns\TextColumn::make('rating')->suffix('/5')->sortable(),Tables\Columns\TextColumn::make('title')->limit(40),Tables\Columns\IconColumn::make('is_approved')->boolean(),Tables\Columns\TextColumn::make('created_at')->date()])->filters([Tables\Filters\TernaryFilter::make('is_approved')])->actions([Tables\Actions\EditAction::make(),Tables\Actions\DeleteAction::make()]);}
 public static function getPages():array{return ['index'=>Pages\ListReviews::route('/'),'edit'=>Pages\EditReview::route('/{record}/edit')];}
}
