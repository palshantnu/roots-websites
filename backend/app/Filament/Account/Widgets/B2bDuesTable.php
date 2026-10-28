<?php

namespace App\Filament\Account\Widgets;

use App\Filament\Account\Resources\B2bProjectResource;
use App\Models\B2bProject;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

/** B2B projects where the client still owes money, biggest first. */
class B2bDuesTable extends TableWidget
{
    protected static bool $isLazy = false;

    protected static ?string $heading = 'Pending collections — B2B projects';

    protected static ?int $sort = 10;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return (bool) auth()->user()?->hasAccountPermission('b2b_projects', 'view');
    }

    public function table(Table $table): Table
    {
        $remaining = 'value - (SELECT COALESCE(SUM(amount), 0) FROM b2b_payments WHERE b2b_payments.b2b_project_id = b2b_projects.id)';

        return $table
            ->query(fn (): Builder => B2bProject::query()
                ->with('client')
                ->withSum('payments', 'amount')
                ->where('status', '!=', 'cancelled')
                ->whereRaw("({$remaining}) > 0")
                ->orderByRaw("({$remaining}) DESC"))
            ->columns([
                Tables\Columns\TextColumn::make('project_no')->label('No.'),
                Tables\Columns\TextColumn::make('client.company_name')
                    ->label('Client')
                    ->description(fn (B2bProject $record) => $record->title),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => B2bProject::STATUSES[$state] ?? $state)
                    ->color(fn (string $state) => B2bProject::statusColor($state)),
                Tables\Columns\TextColumn::make('value')->money('INR'),
                Tables\Columns\TextColumn::make('received')->money('INR')->color('success'),
                Tables\Columns\TextColumn::make('balance')->label('Remaining')->money('INR')->weight('bold')->color('danger'),
            ])
            ->recordUrl(fn (B2bProject $record) => B2bProjectResource::userCan('edit')
                ? B2bProjectResource::getUrl('edit', ['record' => $record])
                : null)
            ->paginated([5, 10, 25])
            ->defaultPaginationPageOption(5)
            ->emptyStateHeading('Nothing pending — all B2B projects are fully paid');
    }
}
