<?php

namespace App\Filament\Clusters\AccountManagement\Resources;

use App\Filament\Clusters\AccountManagement;
use App\Filament\Clusters\AccountManagement\Resources\AccountServiceResource\Pages;
use App\Models\AccountService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AccountServiceResource extends Resource
{
    protected static ?string $model = AccountService::class;

    protected static ?string $cluster = AccountManagement::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationLabel = 'Services';

    protected static ?string $modelLabel = 'service';

    protected static ?string $slug = 'services';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Service name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->helperText('e.g. IT, Research, Publication, Digital Marketing'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('work_streams_count')
                    ->counts('workStreams')
                    ->label('Work streams'),
                Tables\Columns\TextColumn::make('sort_order')
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
            'index' => Pages\ListAccountServices::route('/'),
            'create' => Pages\CreateAccountService::route('/create'),
            'edit' => Pages\EditAccountService::route('/{record}/edit'),
        ];
    }
}
