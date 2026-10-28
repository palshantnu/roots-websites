<?php

namespace App\Filament\Account\Resources\B2bClientResource\Pages;

use App\Filament\Account\Resources\B2bClientResource;
use App\Filament\Account\Resources\B2bProjectResource\Pages\ListB2bProjects;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListB2bClients extends ListRecords
{
    protected static string $resource = B2bClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ListB2bProjects::reportAction(),
            Actions\CreateAction::make()->label('New client'),
        ];
    }
}
