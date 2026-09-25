<?php

namespace Database\Seeders;

use App\Enums\SiteCode;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Package;
use App\Models\Post;
use App\Models\PublishingPackagePage;
use App\Models\Service;
use App\Models\Site;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PublicationSeeder extends Seeder
{
    public function run(): void
    {
        $site = Site::findByCode(SiteCode::Publications);

        $categoryNames = ['Fiction', 'Poetry', 'Academic', 'History', 'Religion', 'Motivational', 'Children', 'Drama', 'Research', 'Autobiography', 'Novel', 'Stories', 'Travelogue', 'Short Stories'];

        foreach ($categoryNames as $index => $name) {
            Category::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'sort_order' => $index]);
        }

        $authorProfiles = [
            ['Raaj Heeraman', 'Author / Poet', 'Poetry and Literature'],
            ['Dr. Achla Nagar', 'Author', 'Fiction'],
            ['Ajay Agrawal', 'Author', 'Academic'],
            ['Dr. Sanjeev Kumar Chaudhary', 'Professor of Surgery', 'Research'],
            ['Varsha Chopade', 'Author, Social Activist', 'Autobiography'],
            ['Dr. Jhorna Sharma', 'Assistant Professor', 'Academic'],
            ['Ashvita Garg', 'Motivational Speaker', 'Motivational'],
            ['Satish Vimal', 'Poet', 'Poetry'],
        ];
        $authorImages = ['1500648767791-00dcc994a43e', '1551836022-d5d88e9218df', '1560250097-0b93528c311a', '1535713875002-d1d0cf377fde', '1580489944761-15a19d654956'];

        foreach ($authorProfiles as $index => [$name, $role, $genre]) {
            Author::firstOrCreate(['slug' => Str::slug($name)], [
                'name' => $name,
                'role' => $role,
                'genre' => $genre,
                'bio' => 'A thoughtful voice with a body of work that invites readers to pause, question and see the familiar differently.',
                'image' => 'https://images.unsplash.com/photo-'.$authorImages[$index % count($authorImages)].'?auto=format&fit=crop&w=500&q=85',
            ]);
        }

        $covers = [
            ['The Art of Becoming', 'N. K. Sharma', 'Fiction', 499, 699, '#d6a36a'],
            ['A Sky Full of Stories', 'Meera Kapoor', 'Stories', 399, 599, '#75978a'],
            ['Letters to My Younger Self', 'Arjun Rai', 'Autobiography', 349, 499, '#c87b6a'],
            ['Nanka Pind', 'Satwinder Singh', 'History', 399, 499, '#9a6d55'],
            ['Samma Sati', 'Dr. Kamlesh Mani Chaudhary', 'Motivational', 399, 499, '#698b81'],
            ['Life in Twilight', 'Debjani Ghosh', 'Poetry', 190, 299, '#b8955b'],
            ['The Corporate Hanuman', 'Dr. Trilok Sharma', 'Religion', 499, 899, '#5c7186'],
            ['Pratidhwani Chhayasutram', 'Dr. Mayukh Mukherjee', 'Other Books', 549, 749, '#bb765a'],
            ['How To Play Cricket For Juniors', 'Aayavanth Mohanty', 'Children', 299, 399, '#779a7d'],
            ['Mastering Economics', 'Ritu Malhotra', 'Academic', 699, 899, '#7b7193'],
            ['Dil Ki Atut Yatra', 'Kavita Singh', 'Poetry', 299, 399, '#c08769'],
            ['Rising Above', 'Ashvita Garg', 'Motivational', 449, 599, '#60858c'],
        ];
        $bookImages = ['1544947950-fa07a98d237f', '1512820790803-83ca734da794', '1495446815901-a7297e633e8d', '1521587760476-6c12a4b040da', '1543002588-bfa74002ed7e', '1544716278-ca5e3f4abd8c'];

        foreach ($covers as $index => [$title, $authorName, $categoryName, $price, $originalPrice, $color]) {
            $category = Category::firstOrCreate(['slug' => Str::slug($categoryName)], ['name' => $categoryName, 'sort_order' => 100 + $index]);
            $author = Author::firstOrCreate(['slug' => Str::slug($authorName)], [
                'name' => $authorName,
                'role' => 'Author',
                'genre' => $categoryName,
                'bio' => 'A thoughtful voice with a body of work that invites readers to pause, question and see the familiar differently.',
                'image' => 'https://images.unsplash.com/photo-'.$authorImages[$index % count($authorImages)].'?auto=format&fit=crop&w=500&q=85',
            ]);

            Book::firstOrCreate(['slug' => Str::slug($title)], [
                'title' => $title,
                'author_id' => $author->id,
                'category_id' => $category->id,
                'price' => $price,
                'original_price' => $originalPrice,
                'color' => $color,
                'cover_image' => 'https://images.unsplash.com/photo-'.$bookImages[$index % count($bookImages)].'?auto=format&fit=crop&w=700&q=85',
                'isbn' => '978-81-RTS-'.(1000 + $index),
                'pages' => 180 + $index * 24,
                'language' => $index % 3 === 0 ? 'Hindi' : 'English',
                'format' => 'Paperback',
                'rating' => 4 + ($index % 2) * 0.5,
                'review_count' => 12 + $index * 7,
                'featured' => $index < 6,
                'bestseller' => $index % 3 === 0,
                'new_release' => $index > 7,
                'description' => 'A carefully crafted '.strtolower($categoryName).' title about memory, possibility and the small choices that shape a life. Prepared with editorial care by RTS Publication.',
            ]);
        }

        $services = [
            ['Book Publishing', 'From manuscript to a finished book with a clear, supportive process.'],
            ['Cover Design', 'A considered cover that catches attention and carries your story.'],
            ['Editing & Proofreading', 'Editorial guidance that makes your words precise and confident.'],
            ['Interior Design', 'Readable, beautiful page design across print and digital formats.'],
            ['ISBN Assistance', 'Practical support through publishing identifiers and metadata.'],
            ['Print & Distribution', 'Reach readers in India and across the world.'],
            ['E-book Publishing', 'Store-ready digital editions for modern readers.'],
            ['Marketing & Publicity', 'Build a discoverable author presence with a tailored plan.'],
            ['Book Launch Support', 'A thoughtful launch that gives your work a strong beginning.'],
        ];

        foreach ($services as $index => [$title, $description]) {
            Service::firstOrCreate(['site_id' => $site->id, 'slug' => Str::slug(str_replace('&', '', $title))], [
                'title' => $title,
                'description' => $description,
                'sort_order' => $index,
            ]);
        }

        $packages = [
            ['Basic', '₹19,999', 'sage', false, ['ISBN assistance', 'Basic cover design', 'Paperback setup', 'Author copies', 'Online distribution']],
            ['Professional', '₹39,999', 'coral', true, ['Everything in Basic', 'Editorial proofreading', 'Premium cover design', 'E-book conversion', 'Marketing starter kit', 'Global distribution']],
            ['Premium', '₹69,999', 'ink', false, ['Everything in Professional', 'Interior design', 'Launch support', 'Author publicity', 'Audiobook consultation', 'Priority account support']],
        ];

        foreach ($packages as $index => [$name, $price, $tone, $popular, $features]) {
            Package::firstOrCreate(['slug' => Str::slug($name)], [
                'name' => $name,
                'price' => $price,
                'tone' => $tone,
                'popular' => $popular,
                'features' => $features,
                'sort_order' => $index,
            ]);
        }

        $packagePages = [
            'paperback-packages' => ['Paperback publishing', 'A beautiful, dependable paperback edition.', 'A reader-friendly format with professional typesetting, cover production and distribution support.'],
            'ebook-packages' => ['E-book publishing', 'Your story, ready for digital shelves.', 'Store-ready digital conversion that helps your book travel across modern reading platforms.'],
            'childrenbook-packages' => ['Children book publishing', 'Big imaginations start here.', 'Illustration, layout and print guidance for books young readers will return to.'],
            'self-publishing-usa' => ['International publishing', 'Take your story beyond borders.', 'A global publishing path with metadata, distribution and launch guidance for international readers.'],
            'book-publishing-uk' => ['UK publishing', 'A publishing route for readers abroad.', 'Extend your reach with an international edition built around discoverability and dependable fulfilment.'],
        ];

        foreach ($packagePages as $slug => [$title, $heading, $copy]) {
            PublishingPackagePage::firstOrCreate(['slug' => $slug], [
                'eyebrow' => 'RTS PACKAGE DETAIL',
                'title' => $title,
                'heading' => $heading,
                'copy' => $copy,
                'benefits' => ['Professional production', 'ISBN and metadata guidance', 'Distribution support', 'Author copies'],
            ]);
        }

        $articleBody = "Every book begins before the cover, in the quiet moment when an idea becomes impossible to ignore. Preparing a manuscript well gives that idea its best chance to reach a reader.\n\nMake room for the work.\n\nRead your pages aloud, keep your audience close and give yourself permission to revise. A strong editorial process is not about changing your voice; it is about helping that voice arrive clearly.";

        $posts = [
            ['How to prepare your manuscript for publication', 'Writing', '2024-08-12', '6 min read', '#d6a36a'],
            ['Finding the right cover for your story', 'Design', '2024-08-03', '4 min read', '#75978a'],
            ['Why every first-time author needs an editor', 'Publishing', '2024-07-28', '5 min read', '#c87b6a'],
        ];

        foreach ($posts as [$title, $category, $date, $readTime, $color]) {
            Post::firstOrCreate(['site_id' => $site->id, 'slug' => Str::slug($title)], [
                'title' => $title,
                'category' => $category,
                'color' => $color,
                'read_time' => $readTime,
                'excerpt' => 'Practical ideas and generous inspiration for writers at every stage.',
                'body' => $articleBody,
                'published_at' => $date,
            ]);
        }

        $faqs = [
            'How does publishing work?',
            'How much does publishing cost?',
            'How long does publishing take?',
            'Do I retain copyright?',
            'Do you provide ISBN assistance?',
            'Do you distribute books?',
            'Do you publish ebooks?',
            'Can first-time authors publish?',
            'What marketing services are available?',
            'How can I track my order?',
        ];

        foreach ($faqs as $index => $question) {
            Faq::firstOrCreate(['site_id' => $site->id, 'question' => $question], [
                'answer' => 'Our publishing advisors guide you through this step with a clear timeline, transparent options and complete support from the RTS team.',
                'sort_order' => $index,
            ]);
        }

        SiteSetting::firstOrCreate(['site_id' => $site->id], [
            'site_name' => 'RTS Publication',
            'tagline' => 'Your story. Our craft.',
            'announcement_text' => 'Free shipping on orders over ₹999 • Publishing consultations available Mon–Sat',
            'hero_eyebrow' => 'A HOME FOR NEW VOICES',
            'hero_title' => 'Turn your manuscript into a published book.',
            'hero_copy' => 'From the first blank page to a book in your hands, RTS Publication brings editorial care, beautiful design and practical distribution together.',
            'stat_community_members' => '2,00,000',
            'stat_registered_writers' => '19,000',
            'stat_books_published' => '3,000',
            'stat_countries_reached' => '180',
            'testimonial_quote' => 'There is no greater agony than bearing an untold story inside you.',
            'testimonial_author' => 'MAYA ANGELOU',
            'contact_email' => 'hello@rtspublication.in',
            'contact_phone' => '+91 79052 66820',
            'contact_address' => 'New Delhi',
            'contact_hours' => 'Monday to Saturday, 10:00 AM – 6:00 PM',
        ]);
    }
}
