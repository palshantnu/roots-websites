<?php

namespace App\Filament\Clusters\It\Resources;

use App\Filament\Clusters\It\ItResource;
use App\Filament\Clusters\It\Resources\ProjectResource\Pages;
use App\Models\Project;
use App\Models\SectionItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProjectResource extends ItResource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Portfolio projects';

    protected static ?int $navigationSort = 3;

    /**
     * Categories come from the "Portfolio filter categories" section so the
     * filter bar and the projects always match.
     *
     * @return array<string, string>
     */
    public static function categoryOptions(): array
    {
        return SectionItem::forSite(static::getSite())
            ->where('section', 'project_categories')
            ->where('title', '!=', 'All')
            ->orderBy('sort_order')
            ->pluck('title', 'title')
            ->all();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Project')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, ?string $state, Forms\Set $set) {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug((string) $state));
                                }
                            }),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->alphaDash()
                            ->unique(ignoreRecord: true, modifyRuleUsing: static::uniqueWithinSite()),
                        Forms\Components\Select::make('category')
                            ->options(fn (): array => static::categoryOptions())
                            ->helperText('Manage the list under Sections → Portfolio filter categories.')
                            ->required(),
                        Forms\Components\TextInput::make('industry')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('year')
                            ->numeric()
                            ->minValue(1990)
                            ->maxValue(2100),
                        Forms\Components\TagsInput::make('technologies')
                            ->placeholder('Add a technology'),
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                        static::imageUpload('image', 'it/projects')
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Visibility')
                    ->columns(3)
                    ->schema([
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured on home page'),
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
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Project $record): ?string => $record->industry),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('year')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\ToggleColumn::make('is_featured')
                    ->label('Featured'),
                Tables\Columns\ToggleColumn::make('is_published')
                    ->label('Published'),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->numeric()
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options(fn (): array => static::categoryOptions()),
                Tables\Filters\TernaryFilter::make('is_featured')->label('Featured'),
                Tables\Filters\TernaryFilter::make('is_published')->label('Published'),
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
