<?php

namespace App\Filament\Account\Resources;

use App\Filament\Account\Concerns\HasModulePermissions;
use App\Filament\Account\Resources\MarketingExpenseResource\Pages;
use App\Models\MarketingChannel;
use App\Models\MarketingExpense;
use App\Models\ProposalCompany;
use App\Models\ProposalPayment;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;

class MarketingExpenseResource extends Resource
{
    use HasModulePermissions;

    protected static string $permissionModule = 'marketing';

    protected static ?string $model = MarketingExpense::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationGroup = 'Marketing';

    protected static ?string $navigationLabel = 'Marketing expenses';

    protected static ?string $modelLabel = 'marketing expense';

    protected static ?string $slug = 'marketing-expenses';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Where did the money go?')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('What was it for?')
                            ->placeholder('e.g. Google search ads — PhD thesis keywords')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('marketing_channel_id')
                            ->label('Channel / platform')
                            ->relationship(
                                name: 'channel',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query) => $query->where('is_active', true)->orderBy('sort_order'),
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm(MarketingChannelResource::fields())
                            ->helperText('Google Ads, Meta, a freelancer, an agency… add more on the fly.'),
                        Forms\Components\TextInput::make('paid_to')
                            ->label('Paid to (person / agency / platform)')
                            ->datalist(fn () => static::distinct('paid_to'))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('campaign')
                            ->label('Campaign (optional)')
                            ->placeholder('e.g. Diwali offer 2026')
                            ->datalist(fn () => static::distinct('campaign'))
                            ->maxLength(255),
                        Forms\Components\Select::make('account_service_id')
                            ->label('Promoting which service')
                            ->relationship(
                                name: 'service',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query) => $query->where('is_active', true)->orderBy('sort_order'),
                            )
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('proposal_company_id')
                            ->label('For which company / brand')
                            ->options(fn () => ProposalCompany::where('is_active', true)->orderBy('sort_order')->pluck('name', 'id')),
                    ]),

                Forms\Components\Section::make('Payment')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('amount')
                            ->numeric()
                            ->prefix('₹')
                            ->minValue(0.01)
                            ->required(),
                        Forms\Components\DatePicker::make('spent_on')
                            ->label('Paid on')
                            ->default(now())
                            ->required(),
                        Forms\Components\Select::make('mode')
                            ->options(ProposalPayment::MODES + ['Card' => 'Card']),
                        Forms\Components\TextInput::make('reference')
                            ->label('Invoice / transaction no.')
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('runs_from')
                            ->label('Campaign runs from'),
                        Forms\Components\DatePicker::make('runs_to')
                            ->label('Campaign runs to')
                            ->afterOrEqual('runs_from'),
                    ]),

                Forms\Components\Section::make('Result & proof')
                    ->columns(2)
                    ->collapsible()
                    ->schema([
                        Forms\Components\TextInput::make('leads')
                            ->label('Leads / enquiries got')
                            ->numeric()
                            ->minValue(0)
                            ->helperText('Optional — used to work out cost per lead.'),
                        Forms\Components\FileUpload::make('receipt')
                            ->label('Bill / receipt / screenshot')
                            ->disk('public')
                            ->directory('marketing-receipts')
                            ->acceptedFileTypes(['image/*', 'application/pdf'])
                            ->maxSize(5120)
                            ->openable()
                            ->downloadable(),
                        Forms\Components\Textarea::make('note')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    /** Earlier values of a text column, offered as suggestions while typing. */
    public static function distinct(string $column): array
    {
        return MarketingExpense::whereNotNull($column)->distinct()->orderBy($column)->pluck($column)->all();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('spent_on')
                    ->label('Date')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->description(fn (MarketingExpense $record) => $record->campaign)
                    ->searchable(['title', 'campaign'])
                    ->limit(45)
                    ->wrap(),
                Tables\Columns\TextColumn::make('channel.name')
                    ->label('Channel')
                    ->badge()
                    ->color('warning')
                    ->sortable(),
                Tables\Columns\TextColumn::make('paid_to')
                    ->searchable()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Service')
                    ->badge()
                    ->color('info')
                    ->placeholder('—')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('INR')
                    ->sortable()
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->money('INR')->label('Total')),
                Tables\Columns\TextColumn::make('leads')
                    ->numeric()
                    ->placeholder('—')
                    ->description(fn (MarketingExpense $record) => ($cpl = MarketingExpense::costPerLead((float) $record->amount, $record->leads))
                        ? Number::currency($cpl, 'INR').' / lead'
                        : null)
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->label('Leads'))
                    ->toggleable(),
                static::addedByColumn(),
            ])
            ->defaultSort('spent_on', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('marketing_channel_id')
                    ->label('Channel')
                    ->relationship('channel', 'name')
                    ->multiple()
                    ->preload(),
                Tables\Filters\SelectFilter::make('account_service_id')
                    ->label('Service')
                    ->relationship('service', 'name')
                    ->multiple()
                    ->preload(),
                Tables\Filters\SelectFilter::make('paid_to')
                    ->label('Paid to')
                    ->options(fn () => array_combine(static::distinct('paid_to'), static::distinct('paid_to')) ?: [])
                    ->searchable(),
                Tables\Filters\SelectFilter::make('campaign')
                    ->options(fn () => array_combine(static::distinct('campaign'), static::distinct('campaign')) ?: [])
                    ->searchable(),
                Tables\Filters\SelectFilter::make('proposal_company_id')
                    ->label('Company')
                    ->relationship('company', 'name'),
                Tables\Filters\Filter::make('spent_on')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Paid from'),
                        Forms\Components\DatePicker::make('until')->label('Paid to'),
                    ])
                    ->query(fn (Builder $query, array $data) => $query
                        ->when($data['from'], fn (Builder $q, $date) => $q->whereDate('spent_on', '>=', $date))
                        ->when($data['until'], fn (Builder $q, $date) => $q->whereDate('spent_on', '<=', $date)))
                    ->indicateUsing(fn (array $data) => collect([
                        $data['from'] ? 'From '.Carbon::parse($data['from'])->format('d M Y') : null,
                        $data['until'] ? 'To '.Carbon::parse($data['until'])->format('d M Y') : null,
                    ])->filter()->values()->all()),
            ])
            ->actions([
                Tables\Actions\Action::make('receipt')
                    ->label('Receipt')
                    ->icon('heroicon-o-paper-clip')
                    ->color('gray')
                    ->visible(fn (MarketingExpense $record) => filled($record->receipt))
                    ->url(fn (MarketingExpense $record) => Storage::disk('public')->url($record->receipt), shouldOpenInNewTab: true),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['channel', 'service', 'creator']);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMarketingExpenses::route('/'),
            'create' => Pages\CreateMarketingExpense::route('/create'),
            'edit' => Pages\EditMarketingExpense::route('/{record}/edit'),
        ];
    }
}
