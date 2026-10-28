<?php

namespace App\Filament\Account\Resources\B2bProjectResource\RelationManagers;

use App\Filament\Account\Resources\B2bProjectResource;
use App\Models\B2bPayment;
use App\Models\B2bProject;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;

/** Payments received on a B2B project, shown on its edit page. */
class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $title = 'Payments received';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return B2bProjectResource::userCan('view');
    }

    public function form(Form $form): Form
    {
        $editing = $form->getRecord();

        return $form
            ->schema(B2bProjectResource::paymentFormSchema(
                $this->getOwnerRecord()->refresh(),
                $editing instanceof B2bPayment ? $editing : null,
            ))
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        /** @var B2bProject $project */
        $project = $this->getOwnerRecord();

        return $table
            ->recordTitleAttribute('amount')
            ->description(fn () => sprintf(
                'Value %s · Received %s · Remaining %s',
                Number::currency((float) $project->value, 'INR'),
                Number::currency($project->refresh()->received, 'INR'),
                Number::currency($project->balance, 'INR'),
            ))
            ->columns([
                Tables\Columns\TextColumn::make('paid_on')
                    ->label('Date')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('INR')
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->money('INR')->label('Total')),
                Tables\Columns\TextColumn::make('mode')
                    ->badge()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('reference')
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
                    ->visible(fn () => $project->received > 0)
                    ->url(fn () => route('b2b.invoice', [$project, 'payment' => 'all'])),
                Tables\Actions\CreateAction::make()
                    ->label('Add payment')
                    ->visible(fn () => $project->refresh()->balance > 0),
            ])
            ->actions([
                Tables\Actions\Action::make('invoice')
                    ->label('Invoice')
                    ->icon('heroicon-o-receipt-percent')
                    ->color('info')
                    ->url(fn (B2bPayment $record) => route('b2b.invoice', [$project, 'payment' => $record->id])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    protected function canCreate(): bool
    {
        return B2bProjectResource::userCan('edit');
    }

    protected function canEdit(Model $record): bool
    {
        return B2bProjectResource::userCan('edit');
    }

    protected function canDelete(Model $record): bool
    {
        return B2bProjectResource::userCan('edit');
    }

    protected function canDeleteAny(): bool
    {
        return B2bProjectResource::userCan('edit');
    }
}
