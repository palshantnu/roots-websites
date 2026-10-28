<?php

namespace App\Filament\Account\Resources;

use App\Filament\Account\Concerns\HasModulePermissions;
use App\Filament\Account\Resources\ProposalResource\Pages;
use App\Filament\Account\Resources\ProposalResource\RelationManagers;
use App\Models\Proposal;
use App\Models\ProposalCompany;
use App\Models\ProposalPayment;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;

class ProposalResource extends Resource
{
    use HasModulePermissions;

    protected static string $permissionModule = 'proposals';

    protected static ?string $model = Proposal::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Proposals';

    protected static ?string $navigationLabel = 'Proposals';

    protected static ?string $modelLabel = 'proposal';

    protected static ?string $recordTitleAttribute = 'proposal_no';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Company & service')
                    ->description('Which company is this proposal from? Its name, tagline and footer go on the PDF.')
                    ->schema([
                        Forms\Components\Radio::make('proposal_company_id')
                            ->label('')
                            ->options(fn () => ProposalCompany::where('is_active', true)
                                ->orderBy('sort_order')
                                ->pluck('name', 'id'))
                            ->required()
                            ->inline()
                            ->disabled(fn (?Proposal $record) => $record?->exists)
                            ->dehydrated(fn (?Proposal $record) => ! $record?->exists),
                        Forms\Components\Select::make('account_service_id')
                            ->label('Service')
                            ->relationship(
                                name: 'service',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query) => $query
                                    ->where('is_active', true)
                                    ->orderBy('sort_order'),
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->unique('account_services', 'name'),
                                Forms\Components\Toggle::make('is_active')
                                    ->default(true),
                            ])
                            ->helperText('Which line of business this work belongs to — used for filters and reports.'),
                    ]),

                Forms\Components\Section::make('Client (for records — not printed)')
                    ->columns(2)
                    ->collapsible()
                    ->schema([
                        Forms\Components\TextInput::make('client_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('client_organization')
                            ->label('Organization / university')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('client_email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('client_phone')
                            ->tel()
                            ->maxLength(50),
                        Forms\Components\TextInput::make('proposal_no')
                            ->label('Proposal no.')
                            ->placeholder('Generated automatically')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\DatePicker::make('proposal_date')
                            ->default(now())
                            ->required(),
                    ]),

                Forms\Components\Section::make('Proposal')
                    ->schema([
                        Forms\Components\TextInput::make('subject')
                            ->label('Proposal title')
                            ->default('PROPOSAL FOR PhD RESEARCH & THESIS SUPPORT')
                            ->helperText('Printed as the heading, exactly as typed.')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('greeting')
                            ->default('Dear Sir/Ma’am,')
                            ->maxLength(255),
                        Forms\Components\RichEditor::make('introduction')
                            ->default('<p>Thank you for considering {company} for your PhD research and thesis support. We are pleased to offer the following package:</p>')
                            ->helperText('{company} is replaced with the company name in bold.')
                            ->toolbarButtons(['bold', 'italic', 'underline'])
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('package_includes')
                            ->label('Package includes')
                            ->default('<ul><li>Complete PhD Thesis – Chapter 1 to Chapter 5</li></ul>')
                            ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList'])
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Payment structure')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->label('Stages')
                            ->schema([
                                Forms\Components\TextInput::make('work')
                                    ->required()
                                    ->columnSpan(3),
                                Forms\Components\TextInput::make('amount')
                                    ->numeric()
                                    ->prefix('₹')
                                    ->minValue(0)
                                    ->required()
                                    ->live(onBlur: true),
                            ])
                            ->columns(4)
                            ->default([
                                ['work' => 'Token Amount', 'amount' => null],
                                ['work' => 'Chapter 1', 'amount' => null],
                            ])
                            ->reorderable()
                            ->cloneable()
                            ->addActionLabel('Add stage')
                            ->live()
                            ->columnSpanFull(),

                        Forms\Components\Placeholder::make('total_preview')
                            ->label('Total package fee')
                            ->content(fn (Get $get) => Proposal::formatAmount(
                                (new Proposal(['items' => array_values($get('items') ?? [])]))->total,
                            )),
                    ]),

                Forms\Components\Section::make('Terms')
                    ->schema([
                        Forms\Components\RichEditor::make('payment_terms')
                            ->default('<ul><li>Payment will be made stage-wise before commencement of the respective stage.</li><li>The next stage will proceed after review/approval of the previous stage.</li></ul>')
                            ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList'])
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('deliverables')
                            ->default('<p>The complete project will be prepared according to the finalized research topic, university guidelines, and mutually agreed requirements.</p>')
                            ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList'])
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Final project')
                    ->description('Record further payments in the Payments list below.')
                    ->columns(4)
                    ->visible(fn (?Proposal $record) => $record?->isFinal())
                    ->schema([
                        Forms\Components\TextInput::make('final_amount')
                            ->numeric()
                            ->prefix('₹')
                            ->required(),
                        Forms\Components\TextInput::make('advance_amount')
                            ->label('Advance received')
                            ->numeric()
                            ->prefix('₹')
                            ->default(0),
                        Forms\Components\Placeholder::make('received_summary')
                            ->label('Total received')
                            ->content(fn (Proposal $record) => Number::currency($record->received, 'INR')),
                        Forms\Components\Placeholder::make('remaining_summary')
                            ->label('Remaining')
                            ->content(fn (Proposal $record) => Number::currency($record->balance, 'INR')),
                    ]),
            ]);
    }

    /** Fields for recording a payment received on a final project. */
    public static function paymentFormSchema(Proposal $proposal, ?ProposalPayment $editing = null): array
    {
        // On edit, the payment's own amount is still available to re-enter.
        $remaining = round($proposal->balance + (float) $editing?->amount, 2);

        return [
            Forms\Components\TextInput::make('amount')
                ->numeric()
                ->prefix('₹')
                ->minValue(0.01)
                ->maxValue(max($remaining, 0))
                ->helperText('Remaining: '.Number::currency($remaining, 'INR'))
                ->required(),
            Forms\Components\DatePicker::make('paid_on')
                ->label('Received on')
                ->default(now())
                ->required(),
            Forms\Components\Select::make('stage')
                ->label('For stage')
                ->options(collect($proposal->items ?? [])->pluck('work', 'work')->filter())
                ->searchable(),
            Forms\Components\Select::make('mode')
                ->options(ProposalPayment::MODES),
            Forms\Components\TextInput::make('note')
                ->maxLength(255)
                ->columnSpanFull(),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('proposal_no')
                    ->label('No.')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->label('Company')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Service')
                    ->badge()
                    ->color('info')
                    ->placeholder('—')
                    ->sortable(),
                Tables\Columns\TextColumn::make('client_name')
                    ->label('Client')
                    ->description(fn (Proposal $record) => $record->client_organization)
                    ->searchable(['client_name', 'client_organization']),
                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->limit(40)
                    ->wrap(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Quoted')
                    ->money('INR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('final_amount')
                    ->money('INR')
                    ->sortable()
                    ->visible(fn ($livewire) => $livewire->activeTab === 'final'),
                Tables\Columns\TextColumn::make('advance_amount')
                    ->label('Advance')
                    ->money('INR')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(fn ($livewire) => $livewire->activeTab === 'final'),
                Tables\Columns\TextColumn::make('received')
                    ->label('Received')
                    ->money('INR')
                    ->color('success')
                    ->description(fn (Proposal $record) => $record->payments_sum_amount
                        ? 'Advance '.Number::currency((float) $record->advance_amount, 'INR')
                        : null)
                    ->visible(fn ($livewire) => $livewire->activeTab === 'final'),
                Tables\Columns\TextColumn::make('balance')
                    ->label('Remaining')
                    ->money('INR')
                    ->weight('bold')
                    ->color(fn ($state): string => $state > 0 ? 'danger' : 'success')
                    ->description(fn ($state) => $state <= 0 ? 'Fully paid' : null)
                    ->visible(fn ($livewire) => $livewire->activeTab === 'final'),
                Tables\Columns\TextColumn::make('proposal_date')
                    ->label('Date')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('finalized_at')
                    ->label('Finalised')
                    ->date('d M Y')
                    ->sortable()
                    ->visible(fn ($livewire) => $livewire->activeTab === 'final'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('proposal_company_id')
                    ->label('Company')
                    ->relationship('company', 'name'),
                Tables\Filters\SelectFilter::make('account_service_id')
                    ->label('Service')
                    ->relationship('service', 'name')
                    ->multiple()
                    ->preload(),
                Tables\Filters\Filter::make('proposal_date')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Date from'),
                        Forms\Components\DatePicker::make('until')->label('Date to'),
                    ])
                    ->query(fn (Builder $query, array $data) => $query
                        ->when($data['from'], fn (Builder $q, $date) => $q->whereDate('proposal_date', '>=', $date))
                        ->when($data['until'], fn (Builder $q, $date) => $q->whereDate('proposal_date', '<=', $date)))
                    ->indicateUsing(fn (array $data) => collect([
                        $data['from'] ? 'From '.Carbon::parse($data['from'])->format('d M Y') : null,
                        $data['until'] ? 'To '.Carbon::parse($data['until'])->format('d M Y') : null,
                    ])->filter()->values()->all()),
            ])
            ->actions([
                Tables\Actions\Action::make('pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->url(fn (Proposal $record) => route('proposals.pdf', $record)),

                static::invoiceAction(Tables\Actions\Action::make('invoice')),

                Tables\Actions\Action::make('finalize')
                    ->label('Final project')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (Proposal $record) => ! $record->isFinal() && static::userCan('edit'))
                    ->modalHeading(fn (Proposal $record) => 'Finalise '.$record->proposal_no)
                    ->modalDescription('The proposal will move to the Final projects tab.')
                    ->fillForm(fn (Proposal $record) => [
                        'final_amount' => $record->total_amount,
                        'advance_amount' => 0,
                    ])
                    ->form([
                        Forms\Components\TextInput::make('final_amount')
                            ->label('Final amount')
                            ->numeric()
                            ->prefix('₹')
                            ->minValue(0)
                            ->required(),
                        Forms\Components\TextInput::make('advance_amount')
                            ->label('Advance amount')
                            ->numeric()
                            ->prefix('₹')
                            ->minValue(0)
                            ->lte('final_amount')
                            ->required(),
                    ])
                    ->action(function (Proposal $record, array $data, $livewire) {
                        $record->update([
                            'status' => Proposal::STATUS_FINAL,
                            'final_amount' => $data['final_amount'],
                            'advance_amount' => $data['advance_amount'],
                            'finalized_at' => now(),
                        ]);

                        Notification::make()
                            ->title($record->proposal_no.' moved to Final projects')
                            ->success()
                            ->send();

                        $livewire->activeTab = 'final';
                    }),

                Tables\Actions\Action::make('addPayment')
                    ->label('Add payment')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn (Proposal $record) => $record->isFinal() && $record->balance > 0 && static::userCan('edit'))
                    ->modalHeading(fn (Proposal $record) => 'Payment received — '.$record->proposal_no)
                    ->modalWidth('lg')
                    ->form(fn (Proposal $record) => static::paymentFormSchema($record))
                    ->action(function (Proposal $record, array $data) {
                        $record->payments()->create($data);

                        Notification::make()
                            ->title('Payment saved')
                            ->body('Remaining: '.Number::currency($record->balance, 'INR'))
                            ->success()
                            ->send();
                    }),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * "Invoice" — download one payment's invoice, or a single invoice of every
     * payment received so far. Shared by the table row and the edit page.
     */
    public static function invoiceAction(Tables\Actions\Action|Actions\Action $action): Tables\Actions\Action|Actions\Action
    {
        return $action
            ->label('Invoice')
            ->icon('heroicon-o-receipt-percent')
            ->color('info')
            ->visible(fn (Proposal $record) => $record->isFinal() && $record->received > 0)
            ->modalHeading(fn (Proposal $record) => 'Download invoice — '.$record->proposal_no)
            ->modalWidth('lg')
            ->modalSubmitActionLabel('Download')
            ->form(fn (Proposal $record) => [
                Forms\Components\Radio::make('payment')
                    ->label('Invoice for')
                    ->options(static::invoiceOptions($record))
                    ->default('all')
                    ->required(),
            ])
            ->action(fn (Proposal $record, array $data, $livewire) => $livewire->js(
                'window.location.href = '.json_encode(route('proposals.invoice', [$record, 'payment' => $data['payment']])).';'
            ));
    }

    /** "All payments" plus the advance and every payment, each on its own. */
    public static function invoiceOptions(Proposal $proposal): array
    {
        $options = ['all' => 'All payments — one combined invoice ('.Number::currency($proposal->received, 'INR').')'];

        foreach ($proposal->receiptLines() as $line) {
            $options[$line['key']] = collect([
                Number::currency($line['amount'], 'INR'),
                $line['date']?->format('d M Y'),
                $line['stage'],
                $line['mode'],
            ])->filter()->implode(' · ');
        }

        return $options;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['company', 'service'])
            ->withSum('payments', 'amount');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PaymentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProposals::route('/'),
            'create' => Pages\CreateProposal::route('/create'),
            'edit' => Pages\EditProposal::route('/{record}/edit'),
        ];
    }
}
