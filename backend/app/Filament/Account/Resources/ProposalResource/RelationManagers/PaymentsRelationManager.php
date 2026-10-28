<?php

namespace App\Filament\Account\Resources\ProposalResource\RelationManagers;

use App\Filament\Account\Resources\ProposalResource;
use App\Models\Proposal;
use App\Models\ProposalPayment;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;

/** Payment history of a final project, shown on its edit page. */
class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $title = 'Payments received';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord instanceof Proposal && $ownerRecord->isFinal() && ProposalResource::userCan('view');
    }

    public function form(Form $form): Form
    {
        $editing = $form->getRecord();

        return $form
            ->schema(ProposalResource::paymentFormSchema(
                $this->getOwnerRecord()->refresh(),
                $editing instanceof ProposalPayment ? $editing : null,
            ))
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        /** @var Proposal $proposal */
        $proposal = $this->getOwnerRecord();

        return $table
            ->recordTitleAttribute('amount')
            ->description(fn () => sprintf(
                'Final %s · Advance %s · Received %s · Remaining %s',
                Number::currency((float) $proposal->final_amount, 'INR'),
                Number::currency((float) $proposal->advance_amount, 'INR'),
                Number::currency($proposal->refresh()->received, 'INR'),
                Number::currency($proposal->balance, 'INR'),
            ))
            ->columns([
                Tables\Columns\TextColumn::make('paid_on')
                    ->label('Date')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stage')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('amount')
                    ->money('INR')
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->money('INR')->label('Total')),
                Tables\Columns\TextColumn::make('mode')
                    ->badge()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('note')
                    ->wrap()
                    ->placeholder('—'),
            ])
            ->headerActions([
                Tables\Actions\Action::make('invoiceAll')
                    ->label('Invoice (all payments)')
                    ->icon('heroicon-o-receipt-percent')
                    ->color('info')
                    ->url(fn () => route('proposals.invoice', [$proposal, 'payment' => 'all'])),
                Tables\Actions\CreateAction::make()
                    ->label('Add payment')
                    ->visible(fn () => $proposal->refresh()->balance > 0),
            ])
            ->actions([
                Tables\Actions\Action::make('invoice')
                    ->label('Invoice')
                    ->icon('heroicon-o-receipt-percent')
                    ->color('info')
                    ->url(fn (ProposalPayment $record) => route('proposals.invoice', [$proposal, 'payment' => $record->id])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    protected function canCreate(): bool
    {
        return ProposalResource::userCan('edit');
    }

    protected function canEdit(Model $record): bool
    {
        return ProposalResource::userCan('edit');
    }

    protected function canDelete(Model $record): bool
    {
        return ProposalResource::userCan('edit');
    }

    protected function canDeleteAny(): bool
    {
        return ProposalResource::userCan('edit');
    }
}
