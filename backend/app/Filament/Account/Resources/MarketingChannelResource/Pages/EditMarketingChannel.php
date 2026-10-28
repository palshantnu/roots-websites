<?php

namespace App\Filament\Account\Resources\MarketingChannelResource\Pages;

use App\Filament\Account\Resources\MarketingChannelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMarketingChannel extends EditRecord
{
    protected static string $resource = MarketingChannelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

