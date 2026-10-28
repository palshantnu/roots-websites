<?php

namespace App\Filament\Account\Resources\B2bClientResource\RelationManagers;

use App\Filament\Account\Resources\B2bProjectResource;
use App\Models\B2bPayment;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

/** Every payment the client has made, across all its projects. Read-only. */
class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $title = 'Payments';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return B2bProjectResource::userCan('view');
    }

    public function isReadOnly(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('amount')
            ->modifyQueryUsing(fn ($query) => $query->with('project'))
            ->columns([
                Tables\Columns\TextColumn::make('paid_on')
                    ->label('Date')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('project.project_no')
                    ->label('Project')
                    ->description(fn (B2bPayment $record) => $record->project?->title),
                Tables\Columns\TextColumn::make('amount')
                    ->money('INR')
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->money('INR')->label('Total')),
                Tables\Columns\TextColumn::make('mode')
                    ->badge()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('reference')
                    ->placeholder('—'),
            ])
            ->defaultSort('paid_on', 'desc')
            ->actions([
                Tables\Actions\Action::make('invoice')
                    ->label('Invoice')
                    ->icon('heroicon-o-receipt-percent')
                    ->color('info')
                    ->url(fn (B2bPayment $record) => route('b2b.invoice', [$record->b2b_project_id, 'payment' => $record->id])),
            ]);
    }
}
