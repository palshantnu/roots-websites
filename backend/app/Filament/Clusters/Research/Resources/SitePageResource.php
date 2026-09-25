<?php

namespace App\Filament\Clusters\Research\Resources;

use App\Filament\Clusters\Research\ResearchResource;
use App\Filament\Clusters\Research\Resources\SitePageResource\Pages;
use App\Models\SitePage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class SitePageResource extends ResearchResource
{
    protected static ?string $model = SitePage::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $navigationLabel = 'Pages & SEO';

    protected static ?string $modelLabel = 'page';

    protected static ?string $slug = 'pages';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Page')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('slug')
                            ->label('Website page')
                            ->options(config('sites.research.pages', []))
                            ->required()
                            ->disabledOn('edit')
                            ->live()
                            ->afterStateUpdated(fn (?string $state, Forms\Set $set) => $set('name', config("sites.research.pages.{$state}")))
                            ->unique(ignoreRecord: true, modifyRuleUsing: static::uniqueWithinSite()),
                        Forms\Components\TextInput::make('name')
                            ->label('Admin name')
                            ->required()
                            ->maxLength(255),
                    ]),
                Forms\Components\Section::make('Hero')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('eyebrow')
                            ->helperText('Small badge above the title.')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('highlight')
                            ->helperText('Optional part of the title shown in the blue italic style.')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('title')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Call to action banner')
                    ->description('Leave empty to use the default banner text.')
                    ->columns(2)
                    ->collapsible()
                    ->schema([
                        Forms\Components\TextInput::make('cta_title')->label('Title')->maxLength(255),
                        Forms\Components\TextInput::make('cta_label')->label('Button label')->maxLength(255),
                        Forms\Components\Textarea::make('cta_description')->label('Description')->rows(2)->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('SEO')
                    ->columns(2)
                    ->collapsible()
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')
                            ->label('Meta title')
                            ->helperText('Browser tab and search result title.')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('meta_description')
                            ->label('Meta description')
                            ->rows(2)
                            ->maxLength(500)
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('og_image')
                            ->label('Social share image')
                            ->image()
                            ->disk('public')
                            ->directory('research/seo')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(2048)
                            ->columnSpanFull(),
                    ]),
                static::publishedToggle(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('meta_title')
                    ->label('Meta title')
                    ->limit(50),
                Tables\Columns\ToggleColumn::make('is_published')
                    ->label('Published'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('name')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSitePages::route('/'),
            'create' => Pages\CreateSitePage::route('/create'),
            'edit' => Pages\EditSitePage::route('/{record}/edit'),
        ];
    }
}
