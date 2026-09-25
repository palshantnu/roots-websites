<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PublishingPackagePageResource\Pages;
use App\Models\PublishingPackagePage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PublishingPackagePageResource extends Resource
{
    protected static ?string $model = PublishingPackagePage::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Package pages';

    protected static ?string $navigationGroup = 'Publications · Site content';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->alphaDash()
                    ->helperText('Matches the frontend route, e.g. paperback-packages'),
                Forms\Components\TextInput::make('eyebrow'),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('heading')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('copy')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('benefits')
                    ->simple(
                        Forms\Components\TextInput::make('benefit')->required()
                    )
                    ->addActionLabel('Add benefit')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('heading')
                    ->limit(50),
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
            'index' => Pages\ListPublishingPackagePages::route('/'),
            'create' => Pages\CreatePublishingPackagePage::route('/create'),
            'edit' => Pages\EditPublishingPackagePage::route('/{record}/edit'),
        ];
    }
}
