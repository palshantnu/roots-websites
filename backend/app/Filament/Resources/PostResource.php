<?php

namespace App\Filament\Resources;

use App\Enums\SiteCode;
use App\Filament\Concerns\ScopedToSite;
use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    use ScopedToSite;

    public static function getSiteCode(): SiteCode
    {
        return SiteCode::Publications;
    }

    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationLabel = 'Journal posts';

    protected static ?string $navigationGroup = 'Publications · Site content';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, string $state, Forms\Set $set) {
                        if ($operation === 'create') {
                            $set('slug', Str::slug($state));
                        }
                    })
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true, modifyRuleUsing: static::uniqueWithinSite())
                    ->alphaDash(),
                Forms\Components\TextInput::make('category'),
                Forms\Components\TextInput::make('read_time')
                    ->label('Read time')
                    ->placeholder('6 min read'),
                Forms\Components\ColorPicker::make('color'),
                Forms\Components\DatePicker::make('published_at')
                    ->label('Published on')
                    ->default(now()),
                Forms\Components\Textarea::make('excerpt')
                    ->rows(2)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('body')
                    ->rows(8)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->badge(),
                Tables\Columns\TextColumn::make('read_time')
                    ->label('Read time'),
                Tables\Columns\TextColumn::make('published_at')
                    ->date()
                    ->sortable(),
            ])
            ->defaultSort('published_at', 'desc')
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
