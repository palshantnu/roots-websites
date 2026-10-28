<?php

namespace App\Filament\Account\Resources\MarketingChannelResource\Pages;

use App\Filament\Account\Resources\MarketingChannelResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMarketingChannel extends CreateRecord
{
    protected static string $resource = MarketingChannelResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

