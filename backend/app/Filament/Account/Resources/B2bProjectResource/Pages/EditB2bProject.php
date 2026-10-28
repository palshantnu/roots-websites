<?php

namespace App\Filament\Account\Resources\B2bProjectResource\Pages;

use App\Filament\Account\Resources\B2bProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditB2bProject extends EditRecord
{
    protected static string $resource = B2bProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            B2bProjectResource::invoiceAction(Actions\Action::make('invoice')),
            Actions\DeleteAction::make(),
        ];
    }
}
