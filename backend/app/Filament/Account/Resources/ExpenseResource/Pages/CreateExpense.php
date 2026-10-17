<?php

namespace App\Filament\Account\Resources\ExpenseResource\Pages;

use App\Filament\Account\Resources\ExpenseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateExpense extends CreateRecord
{
    protected static string $resource = ExpenseResource::class;
}
