<?php

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Faq;
use App\Models\NewsletterSubscriber;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Package;
use App\Models\Post;
use App\Models\PublishEnquiry;
use App\Models\PublishingPackagePage;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('loads every admin panel page for an authenticated user', function () {
    $user = User::factory()->admin()->create();

    $category = Category::factory()->create();
    $author = Author::factory()->create();
    $book = Book::factory()->create(['author_id' => $author->id, 'category_id' => $category->id]);
    $service = Service::factory()->create();
    $package = Package::factory()->create();
    $packagePage = PublishingPackagePage::factory()->create();
    $post = Post::factory()->create();
    $faq = Faq::factory()->create();
    $contactMessage = ContactMessage::factory()->create();
    $publishEnquiry = PublishEnquiry::factory()->create();
    NewsletterSubscriber::factory()->create();
    $order = Order::factory()->create();
    OrderItem::factory()->create(['order_id' => $order->id, 'book_id' => $book->id]);

    $pages = [
        '/admin',
        '/admin/categories', '/admin/categories/create', "/admin/categories/{$category->id}/edit",
        '/admin/authors', '/admin/authors/create', "/admin/authors/{$author->id}/edit",
        '/admin/books', '/admin/books/create', "/admin/books/{$book->id}/edit",
        '/admin/services', '/admin/services/create', "/admin/services/{$service->id}/edit",
        '/admin/packages', '/admin/packages/create', "/admin/packages/{$package->id}/edit",
        '/admin/publishing-package-pages', '/admin/publishing-package-pages/create', "/admin/publishing-package-pages/{$packagePage->id}/edit",
        '/admin/posts', '/admin/posts/create', "/admin/posts/{$post->id}/edit",
        '/admin/faqs', '/admin/faqs/create', "/admin/faqs/{$faq->id}/edit",
        '/admin/contact-messages', "/admin/contact-messages/{$contactMessage->id}/edit",
        '/admin/publish-enquiries', "/admin/publish-enquiries/{$publishEnquiry->id}/edit",
        '/admin/newsletter-subscribers',
        '/admin/orders', "/admin/orders/{$order->id}/edit",
        '/admin/manage-site-settings',
    ];

    foreach ($pages as $page) {
        $this->actingAs($user)->get($page)->assertOk();
    }
});
