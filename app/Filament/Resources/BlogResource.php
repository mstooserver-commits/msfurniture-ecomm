<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Models\Blog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => filled($state) && $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
                        Forms\Components\Select::make('blog_category_id')
                            ->relationship('blogCategory', 'name')
                            ->searchable()
                            ->preload(),
                    ]),
                    Forms\Components\FileUpload::make('featured_image')
                        ->image()
                        ->directory('blog')
                        ->disk('public')
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('excerpt')->rows(2)->columnSpanFull(),
                    Forms\Components\RichEditor::make('content')->required()->columnSpanFull(),
                    Forms\Components\TagsInput::make('tags'),
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Toggle::make('is_published')->live(),
                        Forms\Components\DateTimePicker::make('published_at')
                            ->default(now())
                            ->visible(fn (Forms\Get $get): bool => (bool) $get('is_published')),
                    ]),
                    Forms\Components\TextInput::make('meta_title'),
                    Forms\Components\Textarea::make('meta_description')->rows(2)->columnSpanFull(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')->disk('public')->height(40),
                Tables\Columns\TextColumn::make('title')->searchable()->limit(40),
                Tables\Columns\TextColumn::make('blogCategory.name')->label('Category')->badge(),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
                Tables\Columns\TextColumn::make('published_at')->dateTime()->sortable(),
            ])
            ->defaultSort('published_at', 'desc')
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
}
