<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Models\Book;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = 'Catalogue';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Book')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, string $state, Forms\Set $set) {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug($state));
                                }
                            })
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->alphaDash(),
                        Forms\Components\ColorPicker::make('color'),
                        Forms\Components\Select::make('author_id')
                            ->relationship('author', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\FileUpload::make('cover_image')
                            ->image()
                            ->directory('books')
                            ->columnSpan(2),
                        Forms\Components\Textarea::make('description')
                            ->rows(4)
                            ->columnSpan(2),
                    ]),
                Forms\Components\Section::make('Pricing')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('₹'),
                        Forms\Components\TextInput::make('original_price')
                            ->label('Original price')
                            ->required()
                            ->numeric()
                            ->prefix('₹'),
                    ]),
                Forms\Components\Section::make('Details')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('isbn'),
                        Forms\Components\TextInput::make('pages')->numeric()->default(0),
                        Forms\Components\Select::make('language')
                            ->options(['English' => 'English', 'Hindi' => 'Hindi', 'Other Indian language' => 'Other Indian language'])
                            ->default('English')
                            ->required(),
                        Forms\Components\Select::make('format')
                            ->options(['Paperback' => 'Paperback', 'Hardcover' => 'Hardcover', 'E-book' => 'E-book'])
                            ->default('Paperback')
                            ->required(),
                        Forms\Components\TextInput::make('rating')
                            ->numeric()
                            ->step(0.5)
                            ->minValue(0)
                            ->maxValue(5)
                            ->default(4.5),
                        Forms\Components\TextInput::make('review_count')
                            ->label('Review count')
                            ->numeric()
                            ->default(0),
                    ]),
                Forms\Components\Section::make('Placement')
                    ->columns(3)
                    ->schema([
                        Forms\Components\Toggle::make('featured'),
                        Forms\Components\Toggle::make('bestseller'),
                        Forms\Components\Toggle::make('new_release')->label('New release'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('cover_image')->label(''),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('author.name')
                    ->label('Author')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->formatStateUsing(fn (int $state) => '₹'.number_format($state))
                    ->sortable(),
                Tables\Columns\IconColumn::make('featured')->boolean()->toggleable(),
                Tables\Columns\IconColumn::make('bestseller')->boolean()->toggleable(),
                Tables\Columns\IconColumn::make('new_release')->label('New')->boolean()->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
                Tables\Filters\TernaryFilter::make('featured'),
                Tables\Filters\TernaryFilter::make('bestseller'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
