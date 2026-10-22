<?php

namespace App\Filament\Account\Resources;

use App\Filament\Account\Concerns\HasModulePermissions;
use App\Filament\Account\Resources\ProposalResource\Pages;
use App\Models\Proposal;
use App\Models\ProposalCompany;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
                Forms\Components\Section::make('Company')
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
                    ->columns(2)
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
                    ]),
            ]);
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
                    ->visible(fn ($livewire) => $livewire->activeTab === 'final'),
                Tables\Columns\TextColumn::make('balance')
                    ->label('Balance due')
                    ->money('INR')
                    ->color(fn ($state): string => $state > 0 ? 'danger' : 'success')
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
            ])
            ->actions([
                Tables\Actions\Action::make('pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->url(fn (Proposal $record) => route('proposals.pdf', $record)),

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
        return parent::getEloquentQuery()->with('company');
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
