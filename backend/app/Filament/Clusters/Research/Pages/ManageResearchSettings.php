<?php

namespace App\Filament\Clusters\Research\Pages;

use App\Enums\SiteCode;
use App\Filament\Clusters\Research;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageResearchSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $cluster = Research::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Site settings';

    protected static ?string $title = 'Research site settings';

    protected static ?string $slug = 'settings';

    protected static ?int $navigationSort = 20;

    protected static string $view = 'filament.pages.manage-site-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(SiteSetting::current(SiteCode::Research)->toArray());
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
                            ->helperText('The last word is shown in the blue accent style in the header and footer.')
                            ->required(),
                        Forms\Components\TextInput::make('tagline')
                            ->label('Footer description')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('footer_note')
                            ->label('Footer disclaimer')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Contact')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('contact_email')->label('Email')->email(),
                        Forms\Components\TextInput::make('contact_phone')->label('Phone'),
                        Forms\Components\TextInput::make('contact_address')->label('Address')->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Social links')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('facebook_url')->label('Facebook')->url(),
                        Forms\Components\TextInput::make('twitter_url')->label('Twitter / X')->url(),
                        Forms\Components\TextInput::make('instagram_url')->label('Instagram')->url(),
                        Forms\Components\TextInput::make('linkedin_url')->label('LinkedIn')->url(),
                    ]),
            ]);
    }

    public function save(): void
    {
        SiteSetting::current(SiteCode::Research)->update($this->form->getState());

        Notification::make()
            ->title('Research settings saved')
            ->success()
            ->send();
    }
}
