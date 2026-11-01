<?php
namespace App\Filament\Resources;
use App\Filament\Resources\FaqResource\Pages; use App\Models\Faq; use Filament\Forms; use Filament\Forms\Form; use Filament\Resources\Resource; use Filament\Tables; use Filament\Tables\Table;
class FaqResource extends Resource {
 protected static ?string $model=Faq::class; protected static ?string $navigationIcon='heroicon-o-question-mark-circle'; protected static ?string $navigationGroup='Content';
 public static function form(Form $form):Form{return $form->schema([Forms\Components\TextInput::make('question')->required()->columnSpanFull(),Forms\Components\RichEditor::make('answer')->required()->columnSpanFull(),Forms\Components\Select::make('category')->options(['general'=>'General','delivery'=>'Delivery','returns'=>'Returns','care'=>'Product care','payments'=>'Payments'])->default('general')->required(),Forms\Components\Toggle::make('is_active')->default(true),Forms\Components\TextInput::make('sort_order')->numeric()->default(0)])->columns(3);}
 public static function table(Table $table):Table{return $table->columns([Tables\Columns\TextColumn::make('question')->searchable()->limit(70),Tables\Columns\TextColumn::make('category')->badge(),Tables\Columns\IconColumn::make('is_active')->boolean(),Tables\Columns\TextColumn::make('sort_order')->sortable()])->reorderable('sort_order')->actions([Tables\Actions\EditAction::make(),Tables\Actions\DeleteAction::make()]);}
 public static function getPages():array{return ['index'=>Pages\ListFaqs::route('/'),'create'=>Pages\CreateFaq::route('/create'),'edit'=>Pages\EditFaq::route('/{record}/edit')];}
}
