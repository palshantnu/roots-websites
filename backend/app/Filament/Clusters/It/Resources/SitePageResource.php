<?php

namespace App\Filament\Clusters\It\Resources;

use App\Filament\Clusters\It\ItResource;
use App\Filament\Clusters\It\Resources\SitePageResource\Pages;
use App\Models\SitePage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class SitePageResource extends ItResource
{
    protected static ?string $model = SitePage::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $navigationLabel = 'Pages & SEO';

    protected static ?string $modelLabel = 'page';

    protected static ?string $slug = 'pages';

    protected static ?int $navigationSort = 1;

    public static function isServicePage(?string $slug): bool
    {
        return in_array($slug, static::siteConfig('service_pages'), true);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Page')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('slug')
                            ->label('Website page')
                            ->options(static::siteConfig('pages'))
                            ->required()
                            ->disabledOn('edit')
                            ->live()
                            ->afterStateUpdated(fn (?string $state, Forms\Set $set) => $set('name', static::siteConfig("pages.{$state}", null)))
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
                            ->helperText('Small label above the title.')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('highlight')
                            ->helperText('Optional part of the title shown in the gradient style (home page).')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('title')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->label('Subtitle')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Service page content')
                    ->description('Blocks shown on this service detail page. Empty blocks are hidden.')
                    ->visible(fn (Forms\Get $get): bool => static::isServicePage($get('slug')))
                    ->collapsible()
                    ->statePath('content')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Select::make('category')
                                ->options(static::siteConfig('service_categories')),
                            Forms\Components\TextInput::make('parent_label')
                                ->label('Breadcrumb parent label')
                                ->placeholder('Services'),
                            Forms\Components\TextInput::make('parent_link')
                                ->label('Breadcrumb parent link')
                                ->placeholder('/services'),
                        ]),
                        Forms\Components\Repeater::make('intro')
                            ->label('Overview paragraphs')
                            ->simple(Forms\Components\Textarea::make('text')->rows(3)->required())
                            ->reorderable()
                            ->defaultItems(0),
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('aside_title')
                                ->label('Side panel title')
                                ->placeholder('What’s included'),
                            Forms\Components\TagsInput::make('aside_points')
                                ->label('Side panel points'),
                        ]),
                        Forms\Components\Repeater::make('offerings')
                            ->schema([
                                Forms\Components\TextInput::make('title')->required(),
                                Forms\Components\Select::make('icon')->options(static::iconOptions())->searchable(),
                                Forms\Components\Textarea::make('description')->rows(2)->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->defaultItems(0),
                        Forms\Components\Select::make('technologies')
                            ->label('Technologies')
                            ->helperText('Logos shown in the "Technologies we use" band.')
                            ->multiple()
                            ->options(static::iconOptions('tech_icons')),
                        Forms\Components\Repeater::make('process')
                            ->label('Delivery process')
                            ->schema([
                                Forms\Components\TextInput::make('title')->required(),
                                Forms\Components\Textarea::make('description')->rows(2),
                            ])
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->defaultItems(0),
                        Forms\Components\TagsInput::make('benefits')
                            ->label('Benefits'),
                        Forms\Components\TagsInput::make('industries')
                            ->label('Industries'),
                        Forms\Components\Repeater::make('faqs')
                            ->label('FAQs')
                            ->schema([
                                Forms\Components\TextInput::make('q')->label('Question')->required(),
                                Forms\Components\Textarea::make('a')->label('Answer')->rows(2)->required(),
                            ])
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['q'] ?? null)
                            ->defaultItems(0),
                    ]),
                Forms\Components\Section::make('Call to action banner')
                    ->description('Leave empty to use the default banner text.')
                    ->columns(2)
                    ->collapsible()
                    ->schema([
                        Forms\Components\TextInput::make('cta_title')->label('Title')->maxLength(255)->columnSpanFull(),
                        Forms\Components\Textarea::make('cta_description')->label('Text')->rows(2)->columnSpanFull(),
                        Forms\Components\TextInput::make('cta_label')->label('Primary button')->maxLength(255),
                        Forms\Components\TextInput::make('cta_secondary_label')->label('Secondary button')->maxLength(255),
                    ]),
                Forms\Components\Section::make('SEO')
                    ->columns(2)
                    ->collapsible()
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')
                            ->label('Meta title')
                            ->helperText('The site name is appended automatically. Leave empty on the home page.')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('meta_description')
                            ->label('Meta description')
                            ->rows(2)
                            ->maxLength(500)
                            ->columnSpanFull(),
                        static::imageUpload('og_image', 'it/seo')
                            ->label('Social share image')
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
