<?php

namespace App\Filament\Account\Resources\EmployeeResource\RelationManagers;

use App\Filament\Account\Resources\WorkStreamResource;
use App\Models\WorkStream;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

/** Every project assigned to the employee, with its status and value. */
class WorkStreamsRelationManager extends RelationManager
{
    protected static string $relationship = 'workStreams';

    protected static ?string $title = 'Assigned work';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return WorkStreamResource::userCan('view');
    }

    public function isReadOnly(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Service')
                    ->badge(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => WorkStream::STATUSES[$state] ?? $state)
                    ->color(fn (string $state) => match ($state) {
                        'completed' => 'success',
                        'in_progress' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('cost')
                    ->label('Value')
                    ->money('INR')
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->money('INR')->label('Total')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Added')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(WorkStream::STATUSES),
            ])
            ->actions([
                Tables\Actions\Action::make('open')
                    ->label('Open')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (WorkStream $record) => WorkStreamResource::getUrl('edit', ['record' => $record]))
                    ->visible(fn () => WorkStreamResource::userCan('edit')),
            ]);
    }
}
