<?php

namespace App\Filament\Account\Resources;

use App\Filament\Account\Concerns\HasModulePermissions;
use App\Filament\Account\Resources\EmployeeResource\Pages;
use App\Filament\Account\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EmployeeResource extends Resource
{
    use HasModulePermissions;

    protected static string $permissionModule = 'employees';

    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'People';

    protected static ?string $navigationLabel = 'Employees';

    protected static ?string $modelLabel = 'employee';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Employee details')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('designation')
                            ->placeholder('e.g. Research Writer, Developer')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(50),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('joining_date'),
                        Forms\Components\TextInput::make('monthly_salary')
                            ->label('Monthly salary')
                            ->numeric()
                            ->prefix('₹')
                            ->minValue(0)
                            ->default(0)
                            ->required(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Currently working')
                            ->default(true)
                            ->inline(false),
                        Forms\Components\Textarea::make('note')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->description(fn (Employee $record) => $record->designation)
                    ->searchable(['name', 'designation'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('monthly_salary')
                    ->label('Salary / month')
                    ->money('INR')
                    ->sortable()
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->money('INR')->label('Total payroll')),
                Tables\Columns\TextColumn::make('work_streams_count')
                    ->label('Projects')
                    ->sortable(),
                Tables\Columns\TextColumn::make('completed_work_count')
                    ->label('Completed')
                    ->color('success')
                    ->sortable(),
                Tables\Columns\TextColumn::make('active_work_count')
                    ->label('In hand')
                    ->color('warning')
                    ->sortable(),
                Tables\Columns\TextColumn::make('work_streams_sum_cost')
                    ->label('Work value')
                    ->money('INR')
                    ->placeholder('₹0.00')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('joining_date')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Currently working'),
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
        return parent::getEloquentQuery()
            ->withCount([
                'workStreams',
                'workStreams as completed_work_count' => fn (Builder $query) => $query->where('status', 'completed'),
                'workStreams as active_work_count' => fn (Builder $query) => $query->where('status', '!=', 'completed'),
            ])
            ->withSum('workStreams', 'cost');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\WorkStreamsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
