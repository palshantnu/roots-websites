<?php

namespace App\Filament\Clusters\It\Resources;

use App\Filament\Clusters\It\ItResource;
use App\Filament\Clusters\It\Resources\CaseStudyResource\Pages;
use App\Models\CaseStudy;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CaseStudyResource extends ItResource
{
    protected static ?string $model = CaseStudy::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Case study')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, ?string $state, Forms\Set $set) {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug((string) $state));
                                }
                            })
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->alphaDash()
                            ->unique(ignoreRecord: true, modifyRuleUsing: static::uniqueWithinSite()),
                        Forms\Components\TextInput::make('client')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('industry')
                            ->maxLength(255),
                        Forms\Components\TagsInput::make('technologies'),
                        Forms\Components\Textarea::make('challenge')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('solution')
                            ->rows(3)
                            ->columnSpanFull(),
                        static::imageUpload('image', 'it/case-studies')
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Results')
                    ->schema([
                        Forms\Components\Repeater::make('results')
                            ->schema([
                                Forms\Components\TextInput::make('value')->required()->placeholder('210%'),
                                Forms\Components\TextInput::make('label')->required()->placeholder('Organic traffic growth'),
                            ])
                            ->columns(2)
                            ->maxItems(6)
                            ->defaultItems(0),
                    ]),
                Forms\Components\Section::make('Visibility')
                    ->columns(2)
                    ->schema([
                        static::publishedToggle(),
                        static::sortOrderInput(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('')
                    ->disk('public'),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(50)
                    ->description(fn (CaseStudy $record): ?string => $record->client),
                Tables\Columns\TextColumn::make('industry')
                    ->badge(),
                Tables\Columns\ToggleColumn::make('is_published')
                    ->label('Published'),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->numeric()
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
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
            'index' => Pages\ListCaseStudies::route('/'),
            'create' => Pages\CreateCaseStudy::route('/create'),
            'edit' => Pages\EditCaseStudy::route('/{record}/edit'),
        ];
    }
}
