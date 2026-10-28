<?php

namespace App\Filament\Account\Resources;

use App\Filament\Account\Concerns\HasModulePermissions;
use App\Filament\Account\Resources\RoleResource\Pages;
use App\Models\Role;
use App\Support\AccountPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RoleResource extends Resource
{
    use HasModulePermissions;

    protected static string $permissionModule = 'roles';

    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationGroup = 'Access control';

    protected static ?string $navigationLabel = 'Roles & permissions';

    protected static ?string $modelLabel = 'role';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        $total = count(AccountPermissions::MODULES) * count(AccountPermissions::ACTIONS);

        $moduleFields = collect(AccountPermissions::MODULES)
            ->map(fn (string $label, string $module) => Forms\Components\CheckboxList::make("permissions.{$module}")
                ->label($label)
                ->options(AccountPermissions::ACTIONS)
                ->columns(4)
                ->bulkToggleable()
                ->live()
                ->afterStateUpdated(fn (Get $get, Set $set) => $set(
                    'full_access',
                    collect(AccountPermissions::MODULES)->keys()
                        ->sum(fn ($m) => count($get("permissions.{$m}") ?? [])) === $total,
                )))
            ->values()
            ->all();

        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('description')
                            ->maxLength(255),
                        Forms\Components\Toggle::make('sees_all_records')
                            ->label('Can see everyone\'s records (manager)')
                            ->helperText('Off: users of this role see, edit and delete only the records they added themselves. On: they can work on everyone\'s records, as far as the permissions below allow.')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Permissions')
                    ->description('Tick what this role can do in each module of the Accounts panel.')
                    ->schema([
                        Forms\Components\Toggle::make('full_access')
                            ->label('Full access (add, edit, delete & view everything)')
                            ->dehydrated(false)
                            ->live()
                            ->afterStateHydrated(fn (Forms\Components\Toggle $component, ?Role $record) => $component->state(
                                $record && $record->permissionCount() === $total,
                            ))
                            ->afterStateUpdated(function (Set $set, bool $state) {
                                foreach (array_keys(AccountPermissions::MODULES) as $module) {
                                    $set("permissions.{$module}", $state ? array_keys(AccountPermissions::ACTIONS) : []);
                                }
                            }),
                        Forms\Components\Grid::make(2)->schema($moduleFields),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->wrap(),
                Tables\Columns\TextColumn::make('permissions')
                    ->label('Permissions')
                    ->state(fn (Role $record) => $record->permissionCount().' / '.(count(AccountPermissions::MODULES) * count(AccountPermissions::ACTIONS)))
                    ->badge(),
                Tables\Columns\TextColumn::make('users_count')
                    ->counts('users')
                    ->label('Users'),
                Tables\Columns\TextColumn::make('sees_all_records')
                    ->label('Records')
                    ->badge()
                    ->formatStateUsing(fn (bool $state) => $state ? 'Everyone\'s' : 'Own only')
                    ->color(fn (bool $state) => $state ? 'warning' : 'gray'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
