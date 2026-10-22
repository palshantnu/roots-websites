<?php

namespace App\Filament\Account\Resources;

use App\Filament\Account\Concerns\HasModulePermissions;
use App\Filament\Account\Resources\ExpenseResource\Pages;
use App\Models\Expense;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ExpenseResource extends Resource
{
    use HasModulePermissions;

    protected static string $permissionModule = 'expenses';

    protected static ?string $model = Expense::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-down';

    protected static ?string $navigationGroup = 'Work';

    protected static ?string $navigationLabel = 'Expenses';

    protected static ?string $modelLabel = 'expense';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Expense details')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('What was it for?')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('category')
                            ->options(Expense::CATEGORIES)
                            ->default('Other')
                            ->required()
                            ->searchable(),

                        Forms\Components\Select::make('account_service_id')
                            ->label('Service (optional)')
                            ->relationship(
                                name: 'service',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query) => $query
                                    ->where('is_active', true)
                                    ->orderBy('sort_order'),
                            )
                            ->searchable()
                            ->preload()
                            ->helperText('Tag this expense to a line of business, if it belongs to one.'),

                        Forms\Components\TextInput::make('amount')
                            ->numeric()
                            ->prefix('₹')
                            ->default(0)
                            ->required(),

                        Forms\Components\DatePicker::make('expense_date')
                            ->label('Date')
                            ->default(now())
                            ->required(),

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
                Tables\Columns\TextColumn::make('expense_date')
                    ->label('Date')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Service')
                    ->badge()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('amount')
                    ->money('INR')
                    ->sortable()
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->money('INR')),
            ])
            ->defaultSort('expense_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options(Expense::CATEGORIES),
                Tables\Filters\SelectFilter::make('account_service_id')
                    ->label('Service')
                    ->relationship('service', 'name'),
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
            'index' => Pages\ListExpenses::route('/'),
            'create' => Pages\CreateExpense::route('/create'),
            'edit' => Pages\EditExpense::route('/{record}/edit'),
        ];
    }
}
