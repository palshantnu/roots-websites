<?php

namespace App\Filament\Account\Widgets;

use App\Filament\Account\Resources\ProposalResource;
use App\Models\Proposal;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

/** Final projects where the client still owes money, biggest first. */
class PendingCollectionsTable extends TableWidget
{
    protected static ?string $heading = 'Pending collections — final projects';

    protected static ?int $sort = 8;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return (bool) auth()->user()?->hasAccountPermission('proposals', 'view');
    }

    public function table(Table $table): Table
    {
        $remaining = 'final_amount - COALESCE(advance_amount, 0) - (SELECT COALESCE(SUM(amount), 0) FROM proposal_payments WHERE proposal_payments.proposal_id = proposals.id)';

        return $table
            ->query(fn (): Builder => Proposal::query()
                ->with('company')
                ->withSum('payments', 'amount')
                ->where('status', Proposal::STATUS_FINAL)
                ->whereRaw("({$remaining}) > 0")
                ->orderByRaw("({$remaining}) DESC"))
            ->columns([
                Tables\Columns\TextColumn::make('proposal_no')->label('No.'),
                Tables\Columns\TextColumn::make('client_name')
                    ->label('Client')
                    ->description(fn (Proposal $record) => $record->subject),
                Tables\Columns\TextColumn::make('company.name')->label('Company')->badge(),
                Tables\Columns\TextColumn::make('final_amount')->money('INR'),
                Tables\Columns\TextColumn::make('received')->money('INR')->color('success'),
                Tables\Columns\TextColumn::make('balance')->label('Remaining')->money('INR')->weight('bold')->color('danger'),
            ])
            ->recordUrl(fn (Proposal $record) => ProposalResource::userCan('edit')
                ? ProposalResource::getUrl('edit', ['record' => $record])
                : null)
            ->paginated([5, 10, 25])
            ->defaultPaginationPageOption(5)
            ->emptyStateHeading('Nothing pending — all final projects are fully paid');
    }
}
