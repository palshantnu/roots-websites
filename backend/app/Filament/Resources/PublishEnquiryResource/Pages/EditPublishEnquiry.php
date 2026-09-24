<?php

namespace App\Filament\Resources\PublishEnquiryResource\Pages;

use App\Filament\Resources\PublishEnquiryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPublishEnquiry extends EditRecord
{
    protected static string $resource = PublishEnquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
