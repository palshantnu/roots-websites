<?php

namespace App\Filament\Account\Resources;

use App\Filament\Account\Concerns\HasModulePermissions;
use App\Filament\Account\Forms\ReportFields;
use App\Filament\Account\Resources\B2bClientResource\Pages;
use App\Filament\Account\Resources\B2bClientResource\RelationManagers;
use App\Models\B2bClient;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class B2bClientResource extends Resource
{
    use HasModulePermissions;

    protected static string $permissionModule = 'b2b_clients';

    protected static ?string $model = B2bClient::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'B2B';

    protected static ?string $navigationLabel = 'B2B clients';

    protected static ?string $modelLabel = 'B2B client';

    protected static ?string $slug = 'b2b-clients';

    protected static ?string $recordTitleAttribute = 'company_name';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Company')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('company_name')
                            ->label('Company / organization name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('gstin')
                            ->label('GSTIN')
                            ->maxLength(20),
                        Forms\Components\TextInput::make('pan')
                            ->label('PAN')
                            ->maxLength(20),
                        Forms\Components\TextInput::make('website')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active client')
                            ->default(true)
                            ->inline(false),
                    ]),

                Forms\Components\Section::make('Contact person')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('contact_person')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('designation')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(50),
                        Forms\Components\TextInput::make('alt_phone')
                            ->label('Alternate phone')
                            ->tel()
                            ->maxLength(50),
                    ]),

                Forms\Components\Section::make('Billing address')
                    ->columns(3)
                    ->collapsible()
                    ->schema([
                        Forms\Components\Textarea::make('address')
                            ->rows(2)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('city')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('state')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('pincode')
                            ->maxLength(20),
                    ]),

                Forms\Components\Textarea::make('notes')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    /** Minimal fields for adding a client inline from the project form. */
    public static function quickFields(): array
    {
        return [
            Forms\Components\TextInput::make('company_name')
                ->label('Company / organization name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('contact_person')
                ->maxLength(255),
            Forms\Components\TextInput::make('phone')
                ->tel()
                ->maxLength(50),
            Forms\Components\TextInput::make('email')
                ->email()
                ->maxLength(255),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name')
                    ->label('Client')
                    ->description(fn (B2bClient $record) => collect([$record->contact_person, $record->city])->filter()->implode(' · '))
                    ->searchable(['company_name', 'contact_person', 'city'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->description(fn (B2bClient $record) => $record->email)
                    ->searchable(['phone', 'email'])
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('projects_count')
                    ->label('Projects')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('business')
                    ->label('Total business')
                    ->money('INR')
                    ->sortable(query: fn (Builder $query, string $direction) => $query->orderBy('projects_sum_value', $direction)),
                Tables\Columns\TextColumn::make('received')
                    ->money('INR')
                    ->color('success')
                    ->sortable(query: fn (Builder $query, string $direction) => $query->orderBy('payments_sum_amount', $direction)),
                Tables\Columns\TextColumn::make('balance')
                    ->label('Outstanding')
                    ->money('INR')
                    ->weight('bold')
                    ->color(fn ($state): string => $state > 0 ? 'danger' : 'success'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->toggleable(),
                static::addedByColumn(),
            ])
            ->defaultSort('company_name')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
                Tables\Filters\SelectFilter::make('city')
                    ->options(fn () => B2bClient::whereNotNull('city')->distinct()->orderBy('city')->pluck('city', 'city')),
            ])
            ->actions([
                static::statementAction(Tables\Actions\Action::make('statement')),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /** "Statement" — the client's overall business and payments, as PDF or Excel. */
    public static function statementAction(Tables\Actions\Action|Actions\Action $action): Tables\Actions\Action|Actions\Action
    {
        return $action
            ->label('Statement')
            ->icon('heroicon-o-document-chart-bar')
            ->color('gray')
            ->visible(fn () => B2bProjectResource::userCan('view'))
            ->modalHeading(fn (B2bClient $record) => 'Statement — '.$record->company_name)
            ->modalSubmitActionLabel('Download')
            ->modalWidth('lg')
            ->form([
                ...ReportFields::period('all_time'),
                ReportFields::format(),
            ])
            ->action(fn (B2bClient $record, array $data, $livewire) => $livewire->js(
                ReportFields::downloadJs('b2b.report', ['client' => $record->id] + $data)
            ));
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withTotals();
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ProjectsRelationManager::class,
            RelationManagers\PaymentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListB2bClients::route('/'),
            'create' => Pages\CreateB2bClient::route('/create'),
            'edit' => Pages\EditB2bClient::route('/{record}/edit'),
        ];
    }
}
