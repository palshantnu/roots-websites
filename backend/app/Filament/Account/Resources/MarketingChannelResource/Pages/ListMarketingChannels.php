<?php

namespace App\Filament\Account\Resources\MarketingChannelResource\Pages;

use App\Filament\Account\Resources\MarketingChannelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMarketingChannels extends ListRecords
{
    protected static string $resource = MarketingChannelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

