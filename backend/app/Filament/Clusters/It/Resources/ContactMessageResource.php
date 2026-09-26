<?php

namespace App\Filament\Clusters\It\Resources;

use App\Filament\Clusters\It\ItResource;
use App\Filament\Clusters\It\Resources\ContactMessageResource\Pages;
use App\Filament\Concerns\ManagesContactMessages;
use App\Models\ContactMessage;

class ContactMessageResource extends ItResource
{
    use ManagesContactMessages;

    protected static ?string $model = ContactMessage::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationLabel = 'Enquiries';

    protected static ?string $modelLabel = 'enquiry';

    protected static ?string $pluralModelLabel = 'enquiries';

    protected static ?int $navigationSort = 9;

    protected static function contactTopicColumn(): string
    {
        return 'service';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactMessages::route('/'),
            'edit' => Pages\EditContactMessage::route('/{record}/edit'),
        ];
    }
}
