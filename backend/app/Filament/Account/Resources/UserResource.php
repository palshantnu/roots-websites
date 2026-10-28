<?php

namespace App\Filament\Account\Resources;

use App\Filament\Account\Concerns\HasModulePermissions;
use App\Filament\Account\Forms\ReportFields;
use App\Filament\Account\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Staff logins for the Accounts panel. Super admins (is_admin) are listed but
 * can't be edited or deleted here — they always have full access.
 */
class UserResource extends Resource
{
    use HasModulePermissions;

    protected static string $permissionModule = 'users';

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Access control';

    protected static ?string $navigationLabel = 'Users';

    protected static ?string $modelLabel = 'user';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->minLength(8)
                    ->required(fn (string $operation) => $operation === 'create')
                    ->dehydrated(fn (?string $state) => filled($state))
                    ->helperText(fn (string $operation) => $operation === 'edit' ? 'Leave blank to keep the current password.' : null),
                Forms\Components\Select::make('role_id')
                    ->label('Role')
                    ->relationship('role', 'name')
                    ->preload()
                    ->required(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role.name')
                    ->label('Role')
                    ->badge()
                    ->state(fn (User $record) => $record->is_admin ? 'Super admin' : $record->role?->name)
                    ->color(fn (User $record) => $record->is_admin ? 'warning' : 'primary'),
                Tables\Columns\TextColumn::make('proposals_count')
                    ->label('Proposals created')
                    ->counts('proposals')
                    ->badge()
                    ->color('gray')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role_id')
                    ->label('Role')
                    ->relationship('role', 'name'),
            ])
            ->actions([
                Tables\Actions\Action::make('workReport')
                    ->label('Work report')
                    ->icon('heroicon-o-document-chart-bar')
                    ->color('gray')
                    ->visible(fn (User $record) => ProposalResource::userCan('view') && ($record->proposals_count ?? $record->proposals()->count()) > 0)
                    ->modalHeading(fn (User $record) => 'Proposal work report — '.$record->name)
                    ->modalSubmitActionLabel('Download')
                    ->modalWidth('lg')
                    ->form([
                        ...ReportFields::period(),
                        ReportFields::format(),
                    ])
                    ->action(fn (User $record, array $data, $livewire) => $livewire->js(
                        ReportFields::downloadJs('proposals.report', ['creators' => [$record->id]] + $data)
                    )),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('role');
    }

    public static function canEdit(Model $record): bool
    {
        return ! $record->is_admin && static::userCan('edit');
    }

    public static function canDelete(Model $record): bool
    {
        return ! $record->is_admin && $record->isNot(auth()->user()) && static::userCan('delete');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}
