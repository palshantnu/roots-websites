<?php

namespace App\Filament\Clusters\It\Resources;

use App\Filament\Clusters\It\ItResource;
use App\Filament\Clusters\It\Resources\SectionItemResource\Pages;
use App\Models\SectionItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class SectionItemResource extends ItResource
{
    protected static ?string $model = SectionItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationLabel = 'Sections';

    protected static ?string $modelLabel = 'section item';

    protected static ?string $slug = 'sections';

    protected static ?int $navigationSort = 5;

    /**
     * @return array<string, string>
     */
    public static function sectionOptions(): array
    {
        return collect(static::siteConfig('sections'))
            ->map(fn (array $section): string => $section[0])
            ->all();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(2)
            ->schema([
                Forms\Components\Select::make('section')
                    ->options(static::sectionOptions())
                    ->required()
                    ->live()
                    ->helperText(fn (Forms\Get $get): ?string => static::siteConfig('sections.'.$get('section').'.1', null))
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('subtitle')
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->rows(4)
                    ->columnSpanFull(),
                Forms\Components\Select::make('icon')
                    ->options(static::iconOptions())
                    ->searchable(),
                Forms\Components\TextInput::make('value')
                    ->maxLength(255),
                Forms\Components\TextInput::make('suffix')
                    ->maxLength(20),
                Forms\Components\TextInput::make('meta')
                    ->maxLength(255),
                static::imageUpload('image', 'it/sections')
                    ->columnSpanFull(),
                static::sortOrderInput(),
                static::publishedToggle(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('')
                    ->disk('public')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('section')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => static::sectionOptions()[$state] ?? $state)
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(50)
                    ->description(fn (SectionItem $record): ?string => $record->subtitle),
                Tables\Columns\TextColumn::make('value')
                    ->formatStateUsing(fn (SectionItem $record): string => $record->value.$record->suffix)
                    ->toggleable(),
                Tables\Columns\ToggleColumn::make('is_published')
                    ->label('Published'),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->numeric()
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->defaultGroup('section')
            ->reorderable('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('section')
                    ->options(static::sectionOptions()),
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
            'index' => Pages\ListSectionItems::route('/'),
            'create' => Pages\CreateSectionItem::route('/create'),
            'edit' => Pages\EditSectionItem::route('/{record}/edit'),
        ];
    }
}
