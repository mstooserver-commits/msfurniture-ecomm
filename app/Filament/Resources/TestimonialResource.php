<?php
namespace App\Filament\Resources;
use App\Filament\Resources\TestimonialResource\Pages;
use App\Models\Testimonial;
use Filament\Forms; use Filament\Forms\Form; use Filament\Resources\Resource; use Filament\Tables; use Filament\Tables\Table;
class TestimonialResource extends Resource {
 protected static ?string $model=Testimonial::class; protected static ?string $navigationIcon='heroicon-o-chat-bubble-bottom-center-text'; protected static ?string $navigationGroup='Marketing';
 public static function form(Form $form):Form{return $form->schema([Forms\Components\TextInput::make('name')->required(),Forms\Components\TextInput::make('role'),Forms\Components\Textarea::make('content')->required()->rows(5)->columnSpanFull(),Forms\Components\Select::make('rating')->options(array_combine(range(1,5),range(1,5)))->default(5)->required(),Forms\Components\FileUpload::make('avatar')->image()->disk('public')->directory('testimonials'),Forms\Components\Toggle::make('is_active')->default(true),Forms\Components\TextInput::make('sort_order')->numeric()->default(0)])->columns(2);}
 public static function table(Table $table):Table{return $table->columns([Tables\Columns\TextColumn::make('name')->searchable(),Tables\Columns\TextColumn::make('content')->limit(60),Tables\Columns\TextColumn::make('rating')->suffix('/5'),Tables\Columns\IconColumn::make('is_active')->boolean(),Tables\Columns\TextColumn::make('sort_order')->sortable()])->reorderable('sort_order')->actions([Tables\Actions\EditAction::make(),Tables\Actions\DeleteAction::make()]);}
 public static function getPages():array{return ['index'=>Pages\ListTestimonials::route('/'),'create'=>Pages\CreateTestimonial::route('/create'),'edit'=>Pages\EditTestimonial::route('/{record}/edit')];}
}
