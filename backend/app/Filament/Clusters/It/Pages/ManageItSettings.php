<?php

namespace App\Filament\Clusters\It\Pages;

use App\Enums\SiteCode;
use App\Filament\Clusters\It;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageItSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $cluster = It::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Site settings';

    protected static ?string $title = 'IT site settings';

    protected static ?string $slug = 'settings';

    protected static ?int $navigationSort = 20;

    protected static string $view = 'filament.pages.manage-site-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(SiteSetting::current(SiteCode::It)->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\Section::make('Branding')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('site_name')
                            ->label('Site name')
                            ->helperText('Used in page titles. The first word is the logo text, the rest the sub-label.')
                            ->required(),
                        Forms\Components\TextInput::make('tagline')
                            ->helperText('Shown in the home page title and the footer.')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('founded_year')
                            ->label('Founded in')
                            ->numeric()
                            ->minValue(1900)
                            ->maxValue(2100),
                        Forms\Components\TextInput::make('footer_note')
                            ->label('Copyright line'),
                        Forms\Components\Textarea::make('footer_text')
                            ->label('Footer description')
                            ->helperText('Shown after the tagline in the footer.')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Contact')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('contact_email')->label('Email')->email(),
                        Forms\Components\TextInput::make('contact_phone')->label('Phone'),
                        Forms\Components\Textarea::make('contact_address')
                            ->label('Address')
                            ->helperText('Each line is shown on its own line.')
                            ->rows(2),
                        Forms\Components\TextInput::make('contact_hours')->label('Working hours'),
                        Forms\Components\TextInput::make('map_query')->label('Map location'),
                    ]),
                Forms\Components\Section::make('Social links')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('linkedin_url')->label('LinkedIn')->url(),
                        Forms\Components\TextInput::make('instagram_url')->label('Instagram')->url(),
                        Forms\Components\TextInput::make('facebook_url')->label('Facebook')->url(),
                        Forms\Components\TextInput::make('twitter_url')->label('Twitter / X')->url(),
                    ]),
            ]);
    }

    public function save(): void
    {
        SiteSetting::current(SiteCode::It)->update($this->form->getState());

        Notification::make()
            ->title('IT settings saved')
            ->success()
            ->send();
    }
}
