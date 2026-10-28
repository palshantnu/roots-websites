<?php

namespace App\Filament\Account\Resources;

use App\Filament\Account\Concerns\HasModulePermissions;
use App\Filament\Account\Resources\MarketingChannelResource\Pages;
use App\Models\MarketingChannel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MarketingChannelResource extends Resource
{
    use HasModulePermissions;

    protected static string $permissionModule = 'marketing';

    protected static ?string $model = MarketingChannel::class;

    protected static ?string $navigationIcon = 'heroicon-o-signal';

    protected static ?string $navigationGroup = 'Marketing';

    protected static ?string $navigationLabel = 'Channels / platforms';

    protected static ?string $modelLabel = 'marketing channel';

    protected static ?string $slug = 'marketing-channels';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            ...static::fields(),
            Forms\Components\TextInput::make('sort_order')
                ->numeric()
                ->default(0),
        ]);
    }

    /** Also used to add a channel inline from the expense form. */
    public static function fields(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->label('Channel name')
                ->required()
                ->unique('marketing_channels', 'name', ignoreRecord: true)
                ->helperText('e.g. Google Ads, Meta Ads, Justdial, a freelancer'),
            Forms\Components\Select::make('type')
                ->options(MarketingChannel::TYPES)
                ->default('platform')
                ->selectablePlaceholder(false)
                ->required(),
            Forms\Components\Toggle::make('is_active')
                ->label('Active')
                ->default(true),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => MarketingChannel::TYPES[$state] ?? $state),
                Tables\Columns\TextColumn::make('expenses_count')
                    ->counts('expenses')
                    ->label('Entries'),
                Tables\Columns\TextColumn::make('expenses_sum_amount')
                    ->sum('expenses', 'amount')
                    ->label('Total spent')
                    ->money('INR'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->hidden(fn (MarketingChannel $record) => $record->expenses()->exists()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMarketingChannels::route('/'),
            'create' => Pages\CreateMarketingChannel::route('/create'),
            'edit' => Pages\EditMarketingChannel::route('/{record}/edit'),
        ];
    }
}
