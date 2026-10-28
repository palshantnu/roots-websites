<?php

namespace App\Filament\Account\Resources;

use App\Filament\Account\Concerns\HasModulePermissions;
use App\Filament\Account\Resources\WorkStreamResource\Pages;
use App\Models\Employee;
use App\Models\WorkStream;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class WorkStreamResource extends Resource
{
    use HasModulePermissions;

    protected static string $permissionModule = 'work_streams';

    protected static ?string $model = WorkStream::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Work';

    protected static ?string $navigationLabel = 'Work streams';

    protected static ?string $modelLabel = 'work stream';

    protected static ?string $slug = 'work-streams';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Work details')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Work title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

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
                            ->helperText('IT, Research, Publication, Digital Marketing… add more on the fly.'),

                        Forms\Components\Select::make('employee_id')
                            ->label('Assigned to (employee)')
                            ->relationship(
                                name: 'employee',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query) => $query
                                    ->where('is_active', true)
                                    ->orderBy('name'),
                            )
                            ->getOptionLabelFromRecordUsing(fn (Employee $record) => $record->designation
                                ? "{$record->name} ({$record->designation})"
                                : $record->name)
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                                Forms\Components\TextInput::make('designation'),
                                Forms\Components\TextInput::make('monthly_salary')
                                    ->numeric()
                                    ->prefix('₹')
                                    ->default(0),
                            ]),

                        Forms\Components\Select::make('status')
                            ->options(WorkStream::STATUSES)
                            ->default('in_progress')
                            ->required(),

                        Forms\Components\TextInput::make('cost')
                            ->label('Total cost')
                            ->numeric()
                            ->prefix('₹')
                            ->default(0)
                            ->required(),

                        Forms\Components\TextInput::make('advance_payment')
                            ->label('Advance received')
                            ->numeric()
                            ->prefix('₹')
                            ->default(0),
                    ]),

                Forms\Components\Section::make('Remaining payments')
                    ->description('Pending instalments still to be collected for this work.')
                    ->schema([
                        Forms\Components\Repeater::make('payments')
                            ->relationship()
                            ->label('')
                            ->schema([
                                Forms\Components\TextInput::make('amount')
                                    ->numeric()
                                    ->prefix('₹')
                                    ->default(0)
                                    ->required(),
                                Forms\Components\DatePicker::make('due_date')
                                    ->label('Due date'),
                                Forms\Components\TextInput::make('note')
                                    ->maxLength(255),
                                Forms\Components\Toggle::make('is_paid')
                                    ->label('Paid')
                                    ->inline(false),
                            ])
                            ->columns(2)
                            ->addActionLabel('Add payment')
                            ->defaultItems(0)
                            ->reorderable()
                            ->cloneable()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Note')
                    ->schema([
                        Forms\Components\Textarea::make('note')
                            ->label('')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Service')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('employee.name')
                    ->label('Assigned to')
                    ->placeholder('Unassigned')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => WorkStream::STATUSES[$state] ?? $state)
                    ->color(fn (string $state) => match ($state) {
                        'completed' => 'success',
                        'in_progress' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('cost')
                    ->money('INR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('advance_payment')
                    ->label('Advance')
                    ->money('INR'),
                Tables\Columns\TextColumn::make('balance')
                    ->label('Balance due')
                    ->money('INR')
                    ->color(fn ($state): string => $state > 0 ? 'danger' : 'success'),
                Tables\Columns\TextColumn::make('payments_count')
                    ->counts('payments')
                    ->label('Instalments'),
                static::addedByColumn(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('account_service_id')
                    ->label('Service')
                    ->relationship('service', 'name'),
                Tables\Filters\SelectFilter::make('employee_id')
                    ->label('Employee')
                    ->relationship('employee', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('status')
                    ->options(WorkStream::STATUSES),
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['payments', 'employee']);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkStreams::route('/'),
            'create' => Pages\CreateWorkStream::route('/create'),
            'edit' => Pages\EditWorkStream::route('/{record}/edit'),
        ];
    }
}
