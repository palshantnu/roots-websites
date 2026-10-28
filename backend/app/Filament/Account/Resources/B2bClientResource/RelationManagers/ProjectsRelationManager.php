<?php

namespace App\Filament\Account\Resources\B2bClientResource\RelationManagers;

use App\Filament\Account\Resources\B2bProjectResource;
use App\Models\B2bProject;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/** The client's projects; add one here or open it for payments and invoices. */
class ProjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'projects';

    protected static ?string $title = 'Projects';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return B2bProjectResource::userCan('view');
    }

    public function form(Form $form): Form
    {
        return $form->schema(B2bProjectResource::formFields(withClient: false));
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['service', 'employee'])->withSum('payments', 'amount'))
            ->columns(B2bProjectResource::tableColumns(withClient: false))
            ->defaultSort('start_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(B2bProject::STATUSES)
                    ->multiple(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('New project')
                    ->modalWidth('4xl'),
            ])
            ->actions(B2bProjectResource::tableActions());
    }

    protected function canCreate(): bool
    {
        return B2bProjectResource::userCan('create');
    }

    protected function canEdit(Model $record): bool
    {
        return B2bProjectResource::userCan('edit');
    }

    protected function canDelete(Model $record): bool
    {
        return B2bProjectResource::userCan('delete');
    }

    protected function canDeleteAny(): bool
    {
        return B2bProjectResource::userCan('delete');
    }
}
