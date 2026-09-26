<?php

use App\Enums\SiteCode;
use App\Filament\Clusters\It\Resources\ContactMessageResource\Pages\EditContactMessage as EditItContactMessage;
use App\Filament\Clusters\Research\Resources\ContactMessageResource\Pages\ListContactMessages as ListResearchContactMessages;
use App\Models\ContactMessage;
use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('stores an it enquiry against the it site', function () {
    $this->postJson('/api/it/contact', [
        'name' => 'Asha Rao',
        'email' => 'asha@example.com',
        'company' => 'Lumen',
        'service' => 'Website Development',
        'budget' => '₹1L – ₹3L',
        'message' => 'We need a new marketing site.',
    ])->assertCreated();

    $message = ContactMessage::sole();

    expect($message->site_id)->toBe(Site::findByCode(SiteCode::It)->id)
        ->and($message->service)->toBe('Website Development')
        ->and($message->company)->toBe('Lumen');
});

it('requires a service for it enquiries', function () {
    $this->postJson('/api/it/contact', ['name' => 'A', 'email' => 'a@example.com', 'message' => 'Hello there team'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('service');
});

it('stores a research enquiry without a message', function () {
    $this->postJson('/api/research/contact', [
        'name' => 'Jordan Alvarez',
        'email' => 'jordan@example.com',
        'phone' => '+91 9876543210',
        'subject' => 'Data Analysis',
        'city' => 'Bengaluru',
    ])->assertCreated();

    expect(ContactMessage::sole())
        ->site_id->toBe(Site::findByCode(SiteCode::Research)->id)
        ->city->toBe('Bengaluru')
        ->message->toBeNull();
});

it('requires phone, subject and city for research enquiries', function () {
    $this->postJson('/api/research/contact', ['name' => 'A', 'email' => 'a@example.com'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['phone', 'subject', 'city']);
});

it('keeps publication contact messages on the publications site', function () {
    $this->postJson('/api/publication/contact', ['name' => 'A', 'email' => 'a@example.com', 'message' => 'Hi'])
        ->assertCreated();

    expect(ContactMessage::sole()->site_id)->toBe(Site::findByCode(SiteCode::Publications)->id);
});

it('shows each site only its own enquiries in the admin', function () {
    $this->actingAs(User::factory()->admin()->create());

    $research = ContactMessage::factory()->forSite(SiteCode::Research)->create();
    $it = ContactMessage::factory()->forSite(SiteCode::It)->create();
    $publication = ContactMessage::factory()->create();

    Livewire::test(ListResearchContactMessages::class)
        ->assertCanSeeTableRecords([$research])
        ->assertCanNotSeeTableRecords([$it, $publication]);

    $this->get('/admin/contact-messages')->assertOk()->assertSee($publication->name)->assertDontSee($research->name);

    Livewire::test(EditItContactMessage::class, ['record' => $it->getRouteKey()])->assertOk();
    $this->get("/admin/it/contact-messages/{$research->id}/edit")->assertNotFound();
});
