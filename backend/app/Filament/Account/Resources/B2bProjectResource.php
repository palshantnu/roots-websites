<?php

namespace App\Filament\Account\Resources;

use App\Filament\Account\Concerns\HasModulePermissions;
use App\Filament\Account\Resources\B2bProjectResource\Pages;
use App\Filament\Account\Resources\B2bProjectResource\RelationManagers;
use App\Models\B2bPayment;
use App\Models\B2bProject;
use App\Models\Employee;
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

class B2bProjectResource extends Resource
{
    use HasModulePermissions;

    protected static string $permissionModule = 'b2b_projects';

    protected static ?string $model = B2bProject::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'B2B';

    protected static ?string $navigationLabel = 'B2B projects';

    protected static ?string $modelLabel = 'B2B project';

    protected static ?string $slug = 'b2b-projects';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema(static::formFields());
    }

    /** Project fields; without the client picker when added from a client's page. */
    public static function formFields(bool $withClient = true): array
    {
        return [
            Forms\Components\Section::make('Project')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('b2b_client_id')
                        ->label('B2B client')
                        ->relationship(
                            name: 'client',
                            titleAttribute: 'company_name',
                            modifyQueryUsing: fn (Builder $query) => $query->where('is_active', true)->orderBy('company_name'),
                        )
                        ->searchable()
                        ->preload()
                        ->required()
                        ->createOptionForm(B2bClientResource::quickFields())
                        ->visible($withClient),
                    Forms\Components\TextInput::make('project_no')
                        ->label('Project no.')
                        ->placeholder('Generated automatically')
                        ->disabled()
                        ->dehydrated(false),
                    Forms\Components\TextInput::make('title')
                        ->label('Project title')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Forms\Components\Select::make('account_service_id')
                        ->label('Service')
                        ->relationship(
                            name: 'service',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn (Builder $query) => $query->where('is_active', true)->orderBy('sort_order'),
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
                        ]),
                    Forms\Components\Select::make('employee_id')
                        ->label('Assigned to (employee)')
                        ->relationship(
                            name: 'employee',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn (Builder $query) => $query->where('is_active', true)->orderBy('name'),
                        )
                        ->getOptionLabelFromRecordUsing(fn (Employee $record) => $record->designation
                            ? "{$record->name} ({$record->designation})"
                            : $record->name)
                        ->searchable()
                        ->preload(),
                    Forms\Components\Radio::make('proposal_company_id')
                        ->label('Billed by (company on the invoice)')
                        ->options(fn () => ProposalCompany::where('is_active', true)->orderBy('sort_order')->pluck('name', 'id'))
                        ->default(fn () => ProposalCompany::where('is_active', true)->orderBy('sort_order')->value('id'))
                        ->required()
                        ->inline()
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('description')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Value & timeline')
                ->columns(3)
                ->schema([
                    Forms\Components\TextInput::make('value')
                        ->label('Project value')
                        ->numeric()
                        ->prefix('₹')
                        ->minValue(0)
                        ->required(),
                    Forms\Components\Select::make('status')
                        ->options(B2bProject::STATUSES)
                        ->default('in_progress')
                        ->selectablePlaceholder(false)
                        ->live()
                        ->required(),
                    Forms\Components\DatePicker::make('start_date')
                        ->default(now())
                        ->required(),
                    Forms\Components\DatePicker::make('due_date')
                        ->label('Deadline')
                        ->afterOrEqual('start_date'),
                    Forms\Components\DatePicker::make('completed_on')
                        ->visible(fn (Get $get) => $get('status') === 'completed'),
                    Forms\Components\Placeholder::make('received_summary')
                        ->label('Received / remaining')
                        ->content(fn (?B2bProject $record) => Number::currency($record->received, 'INR')
                            .' / '.Number::currency($record->balance, 'INR'))
                        ->visible(fn (?B2bProject $record) => $record?->exists),
                    Forms\Components\Textarea::make('notes')
                        ->rows(2)
                        ->columnSpanFull(),
                ]),
        ];
    }

    /** Fields for recording a payment received on a project. */
    public static function paymentFormSchema(B2bProject $project, ?B2bPayment $editing = null): array
    {
        $remaining = round($project->balance + (float) $editing?->amount, 2);

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
            Forms\Components\Select::make('mode')
                ->options(ProposalPayment::MODES),
            Forms\Components\TextInput::make('reference')
                ->label('Reference / UTR / cheque no.')
                ->maxLength(255),
            Forms\Components\TextInput::make('note')
                ->maxLength(255)
                ->columnSpanFull(),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(static::tableColumns())
            ->defaultSort('start_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('b2b_client_id')
                    ->label('Client')
                    ->relationship('client', 'company_name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('account_service_id')
                    ->label('Service')
                    ->relationship('service', 'name')
                    ->multiple()
                    ->preload(),
                Tables\Filters\SelectFilter::make('status')
                    ->options(B2bProject::STATUSES)
                    ->multiple(),
                Tables\Filters\SelectFilter::make('proposal_company_id')
                    ->label('Billed by')
                    ->relationship('company', 'name'),
                Tables\Filters\SelectFilter::make('employee_id')
                    ->label('Employee')
                    ->relationship('employee', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\Filter::make('start_date')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Start from'),
                        Forms\Components\DatePicker::make('until')->label('Start to'),
                    ])
                    ->query(fn (Builder $query, array $data) => $query
                        ->when($data['from'], fn (Builder $q, $date) => $q->whereDate('start_date', '>=', $date))
                        ->when($data['until'], fn (Builder $q, $date) => $q->whereDate('start_date', '<=', $date)))
                    ->indicateUsing(fn (array $data) => collect([
                        $data['from'] ? 'From '.Carbon::parse($data['from'])->format('d M Y') : null,
                        $data['until'] ? 'To '.Carbon::parse($data['until'])->format('d M Y') : null,
                    ])->filter()->values()->all()),
                Tables\Filters\Filter::make('with_dues')
                    ->label('Money due')
                    ->toggle()
                    ->query(fn (Builder $query) => $query
                        ->where('status', '!=', 'cancelled')
                        ->whereRaw('value > (SELECT COALESCE(SUM(amount), 0) FROM b2b_payments WHERE b2b_payments.b2b_project_id = b2b_projects.id)')),
            ])
            ->actions(static::tableActions())
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /** Columns shared with the client's Projects tab. */
    public static function tableColumns(bool $withClient = true): array
    {
        return [
            Tables\Columns\TextColumn::make('project_no')
                ->label('No.')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('client.company_name')
                ->label('Client')
                ->searchable()
                ->sortable()
                ->visible($withClient),
            Tables\Columns\TextColumn::make('title')
                ->description(fn (B2bProject $record) => $record->service?->name)
                ->searchable()
                ->limit(45)
                ->wrap(),
            Tables\Columns\TextColumn::make('status')
                ->badge()
                ->formatStateUsing(fn (string $state) => B2bProject::STATUSES[$state] ?? $state)
                ->color(fn (string $state) => B2bProject::statusColor($state)),
            Tables\Columns\TextColumn::make('value')
                ->money('INR')
                ->sortable()
                ->summarize(Tables\Columns\Summarizers\Sum::make()->money('INR')->label('Total')),
            Tables\Columns\TextColumn::make('received')
                ->money('INR')
                ->color('success'),
            Tables\Columns\TextColumn::make('balance')
                ->label('Remaining')
                ->money('INR')
                ->weight('bold')
                ->color(fn ($state): string => $state > 0 ? 'danger' : 'success')
                ->description(fn ($state) => $state <= 0 ? 'Fully paid' : null),
            Tables\Columns\TextColumn::make('employee.name')
                ->label('Assigned to')
                ->placeholder('—')
                ->toggleable(),
            Tables\Columns\TextColumn::make('start_date')
                ->label('Start')
                ->date('d M Y')
                ->sortable()
                ->toggleable(),
            Tables\Columns\TextColumn::make('due_date')
                ->label('Deadline')
                ->date('d M Y')
                ->sortable()
                ->placeholder('—')
                ->color(fn (B2bProject $record) => $record->due_date?->isPast() && ! in_array($record->status, ['completed', 'cancelled']) ? 'danger' : null),
        ];
    }

    public static function tableActions(): array
    {
        return [
            Tables\Actions\Action::make('addPayment')
                ->label('Add payment')
                ->icon('heroicon-o-banknotes')
                ->color('success')
                ->visible(fn (B2bProject $record) => $record->balance > 0 && $record->status !== 'cancelled' && static::userCan('edit'))
                ->modalHeading(fn (B2bProject $record) => 'Payment received — '.$record->project_no)
                ->modalWidth('lg')
                ->form(fn (B2bProject $record) => static::paymentFormSchema($record))
                ->action(function (B2bProject $record, array $data) {
                    $record->payments()->create($data);

                    Notification::make()
                        ->title('Payment saved')
                        ->body('Remaining: '.Number::currency($record->balance, 'INR'))
                        ->success()
                        ->send();
                }),

            static::invoiceAction(Tables\Actions\Action::make('invoice')),

            Tables\Actions\EditAction::make()
                ->url(fn (B2bProject $record) => static::getUrl('edit', ['record' => $record])),
            Tables\Actions\DeleteAction::make()
                ->visible(fn () => static::userCan('delete')),
        ];
    }

    /** "Invoice" — one payment's invoice, or a single invoice of every payment. */
    public static function invoiceAction(Tables\Actions\Action|Actions\Action $action): Tables\Actions\Action|Actions\Action
    {
        return $action
            ->label('Invoice')
            ->icon('heroicon-o-receipt-percent')
            ->color('info')
            ->visible(fn (B2bProject $record) => $record->received > 0)
            ->modalHeading(fn (B2bProject $record) => 'Download invoice — '.$record->project_no)
            ->modalWidth('lg')
            ->modalSubmitActionLabel('Download')
            ->form(fn (B2bProject $record) => [
                Forms\Components\Radio::make('payment')
                    ->label('Invoice for')
                    ->options(static::invoiceOptions($record))
                    ->default('all')
                    ->required(),
            ])
            ->action(fn (B2bProject $record, array $data, $livewire) => $livewire->js(
                'window.location.href = '.json_encode(route('b2b.invoice', [$record, 'payment' => $data['payment']])).';'
            ));
    }

    public static function invoiceOptions(B2bProject $project): array
    {
        $options = ['all' => 'All payments — one combined invoice ('.Number::currency($project->received, 'INR').')'];

        foreach ($project->receiptLines() as $line) {
            $options[$line['key']] = collect([
                Number::currency($line['amount'], 'INR'),
                $line['date']?->format('d M Y'),
                $line['mode'],
                $line['stage'],
            ])->filter()->implode(' · ');
        }

        return $options;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['client', 'service', 'employee'])
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
            'index' => Pages\ListB2bProjects::route('/'),
            'create' => Pages\CreateB2bProject::route('/create'),
            'edit' => Pages\EditB2bProject::route('/{record}/edit'),
        ];
    }
}
