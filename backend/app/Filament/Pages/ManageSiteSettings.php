<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageSiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Site settings';

    protected static ?string $navigationGroup = 'Site content';

    protected static string $view = 'filament.pages.manage-site-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(SiteSetting::current()->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\Section::make('Branding')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('site_name')->label('Site name')->required(),
                        Forms\Components\TextInput::make('tagline'),
                        Forms\Components\TextInput::make('announcement_text')
                            ->label('Announcement bar')
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Homepage hero')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('hero_eyebrow')->label('Eyebrow')->columnSpanFull(),
                        Forms\Components\TextInput::make('hero_title')->label('Title')->columnSpanFull(),
                        Forms\Components\Textarea::make('hero_copy')->label('Copy')->rows(3)->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Stats strip')
                    ->columns(4)
                    ->schema([
                        Forms\Components\TextInput::make('stat_community_members')->label('Community members'),
                        Forms\Components\TextInput::make('stat_registered_writers')->label('Registered writers'),
                        Forms\Components\TextInput::make('stat_books_published')->label('Books published'),
                        Forms\Components\TextInput::make('stat_countries_reached')->label('Countries reached'),
                    ]),
                Forms\Components\Section::make('Testimonial')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Textarea::make('testimonial_quote')->label('Quote')->rows(2)->columnSpanFull(),
                        Forms\Components\TextInput::make('testimonial_author')->label('Attribution'),
                    ]),
                Forms\Components\Section::make('Contact')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('contact_email')->label('Email')->email(),
                        Forms\Components\TextInput::make('contact_phone')->label('Phone'),
                        Forms\Components\TextInput::make('contact_address')->label('Address'),
                        Forms\Components\TextInput::make('contact_hours')->label('Hours'),
                    ]),
                Forms\Components\Section::make('Social links')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('instagram_url')->label('Instagram')->url(),
                        Forms\Components\TextInput::make('facebook_url')->label('Facebook')->url(),
                        Forms\Components\TextInput::make('linkedin_url')->label('LinkedIn')->url(),
                    ]),
            ]);
    }

    public function save(): void
    {
        SiteSetting::current()->update($this->form->getState());

        Notification::make()
            ->title('Site settings saved')
            ->success()
            ->send();
    }
}
