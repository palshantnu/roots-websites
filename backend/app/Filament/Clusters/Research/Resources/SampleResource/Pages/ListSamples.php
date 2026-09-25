<?php

namespace App\Filament\Clusters\Research\Resources\SampleResource\Pages;

use App\Enums\SampleType;
use App\Filament\Clusters\Research\Resources\SampleResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListSamples extends ListRecords
{
    protected static string $resource = SampleResource::class;

    protected function getHeaderActions(): array
    {
        $activeType = SampleType::tryFrom((string) $this->activeTab);

        return [
            Actions\CreateAction::make()
                ->label($activeType ? 'New '.$activeType->getLabel() : 'New sample')
                ->url(SampleResource::getUrl('create', $activeType ? ['type' => $activeType->value] : [])),
        ];
    }

    /**
     * One tab per sample category: Research Papers, Thesis and Synopsis.
     */
    public function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make('All samples')
                ->badge(fn () => SampleResource::getEloquentQuery()->count()),
        ];

        foreach (SampleType::cases() as $type) {
            $tabs[$type->value] = Tab::make($type->pluralLabel())
                ->modifyQueryUsing(fn (Builder $query) => $query->ofType($type))
                ->badge(fn () => SampleResource::getEloquentQuery()->ofType($type)->count());
        }

        return $tabs;
    }
}
