<?php

namespace App\Filament\Account\Resources\MarketingExpenseResource\Pages;

use App\Filament\Account\Resources\MarketingExpenseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMarketingExpense extends CreateRecord
{
    protected static string $resource = MarketingExpenseResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
