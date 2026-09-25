<?php

namespace App\Filament\Clusters\It\Resources;

use App\Filament\Clusters\It\ItResource;
use App\Filament\Clusters\It\Resources\PostResource\Pages;
use App\Models\Post;
use App\Models\SectionItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PostResource extends ItResource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationLabel = 'Blog articles';

    protected static ?string $modelLabel = 'article';

    protected static ?string $slug = 'articles';

    protected static ?int $navigationSort = 8;

    /**
     * @return array<string, string>
     */
    public static function categoryOptions(): array
    {
        return SectionItem::forSite(static::getSite())
            ->where('section', 'blog_categories')
            ->where('title', '!=', 'All')
            ->orderBy('sort_order')
            ->pluck('title', 'title')
            ->all();
    }

    public static function form(Form $form): Form
    {
        return $form
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
                Forms\Components\Select::make('category')
                    ->options(fn (): array => static::categoryOptions())
                    ->helperText('Manage the list under Sections → Blog filter categories.')
                    ->required(),
                Forms\Components\TextInput::make('author'),
                Forms\Components\DatePicker::make('published_at')
                    ->label('Published on')
                    ->default(now())
                    ->required(),
                Forms\Components\Textarea::make('excerpt')
                    ->rows(2)
                    ->columnSpanFull(),
                static::imageUpload('image', 'it/blog')
                    ->label('Cover image')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('body')
                    ->helperText('Separate paragraphs with a blank line. Start a line with "## " for a heading, "### " for a sub-heading and "- " for a list item.')
                    ->rows(14)
                    ->columnSpanFull(),
                static::publishedToggle(),
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
                    ->limit(50),
                Tables\Columns\TextColumn::make('category')
                    ->badge(),
                Tables\Columns\TextColumn::make('author'),
                Tables\Columns\ToggleColumn::make('is_published')
                    ->label('Published'),
                Tables\Columns\TextColumn::make('published_at')
                    ->date()
                    ->sortable(),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options(fn (): array => static::categoryOptions()),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
