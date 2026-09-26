<?php

namespace App\Filament\Clusters\Research\Resources;

use App\Filament\Clusters\Research\ResearchResource;
use App\Filament\Clusters\Research\Resources\ContactMessageResource\Pages;
use App\Filament\Concerns\ManagesContactMessages;
use App\Models\ContactMessage;

class ContactMessageResource extends ResearchResource
{
    use ManagesContactMessages;

    protected static ?string $model = ContactMessage::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationLabel = 'Enquiries';

    protected static ?string $modelLabel = 'enquiry';

    protected static ?string $pluralModelLabel = 'enquiries';

    protected static ?int $navigationSort = 7;

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactMessages::route('/'),
            'edit' => Pages\EditContactMessage::route('/{record}/edit'),
        ];
    }
}
