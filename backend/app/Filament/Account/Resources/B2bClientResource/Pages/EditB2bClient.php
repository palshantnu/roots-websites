<?php

namespace App\Filament\Account\Resources\B2bClientResource\Pages;

use App\Filament\Account\Resources\B2bClientResource;
use App\Models\B2bClient;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Number;

class EditB2bClient extends EditRecord
{
    protected static string $resource = B2bClientResource::class;

    public function getSubheading(): ?string
    {
        /** @var B2bClient $client */
        $client = $this->getRecord();

        return sprintf(
            'Total business %s · Received %s · Outstanding %s',
            Number::currency($client->business, 'INR'),
            Number::currency($client->received, 'INR'),
            Number::currency($client->balance, 'INR'),
        );
    }

    protected function getHeaderActions(): array
    {
        return [
            B2bClientResource::statementAction(Actions\Action::make('statement')),
            Actions\DeleteAction::make(),
        ];
    }
}
