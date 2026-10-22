<?php

namespace App\Filament\Account\Resources;

use App\Filament\Account\Concerns\HasModulePermissions;
use App\Filament\Account\Resources\ProposalCompanyResource\Pages;
use App\Models\ProposalCompany;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProposalCompanyResource extends Resource
{
    use HasModulePermissions;

    protected static string $permissionModule = 'proposal_companies';

    protected static ?string $model = ProposalCompany::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Proposals';

    protected static ?string $navigationLabel = 'Companies';

    protected static ?string $modelLabel = 'company';

    protected static ?string $slug = 'proposal-companies';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('code')
                    ->label('Proposal no. prefix')
                    ->helperText('e.g. RT → RT/2026/0001')
                    ->required()
                    ->alphaDash()
                    ->maxLength(20)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('tagline')
                    ->helperText('Shown under the company name, e.g. Research & Writing Services')
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('logo')
                    ->image()
                    ->disk('public')
                    ->directory('proposal-companies')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('address')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('email')
                    ->email(),
                Forms\Components\TextInput::make('phone')
                    ->tel(),
                Forms\Components\TextInput::make('website'),
                Forms\Components\TextInput::make('gstin')
                    ->label('GSTIN'),
                Forms\Components\TextInput::make('footer_note')
                    ->label('Footer line')
                    ->helperText('Printed in italics at the bottom, e.g. Professional • Confidential • Timely Support')
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('is_active')
                    ->default(true)
                    ->inline(false),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->disk('public')
                    ->height(32),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->badge(),
                Tables\Columns\TextColumn::make('proposals_count')
                    ->counts('proposals')
                    ->label('Proposals'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->hidden(fn (ProposalCompany $record) => $record->proposals()->exists()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProposalCompanies::route('/'),
        ];
    }
}
