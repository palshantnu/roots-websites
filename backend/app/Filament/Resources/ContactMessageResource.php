<?php

namespace App\Filament\Resources;

use App\Enums\SiteCode;
use App\Filament\Concerns\ManagesContactMessages;
use App\Filament\Concerns\ScopedToSite;
use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use Filament\Resources\Resource;

class ContactMessageResource extends Resource
{
    use ManagesContactMessages, ScopedToSite;

    public static function getSiteCode(): SiteCode
    {
        return SiteCode::Publications;
    }

    protected static ?string $model = ContactMessage::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Publications · Enquiries';

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactMessages::route('/'),
            'edit' => Pages\EditContactMessage::route('/{record}/edit'),
        ];
    }
}
