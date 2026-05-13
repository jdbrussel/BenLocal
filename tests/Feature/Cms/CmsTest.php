<?php

namespace Tests\Feature\Cms;

use App\Models\Page;
use App\Models\Faq;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_fetch_published_page_by_slug(): void
    {
        Page::create([
            'slug' => 'test-page',
            'title' => ['en' => 'Test Page'],
            'content' => ['en' => 'Test Content'],
            'published_at' => now()->subDay(),
        ]);

        $response = $this->getJson('/api/pages/test-page');

        $response->assertStatus(200)
            ->assertJsonPath('slug', 'test-page')
            ->assertJsonPath('title.en', 'Test Page');
    }

    public function test_cannot_fetch_unpublished_page(): void
    {
        Page::create([
            'slug' => 'unpublished-page',
            'title' => ['en' => 'Unpublished'],
            'content' => ['en' => 'Content'],
            'published_at' => null,
        ]);

        $response = $this->getJson('/api/pages/unpublished-page');

        $response->assertStatus(404);
    }

    public function test_cannot_fetch_future_published_page(): void
    {
        Page::create([
            'slug' => 'future-page',
            'title' => ['en' => 'Future'],
            'content' => ['en' => 'Content'],
            'published_at' => now()->addDay(),
        ]);

        $response = $this->getJson('/api/pages/future-page');

        $response->assertStatus(404);
    }

    public function test_can_fetch_faqs(): void
    {
        Faq::create([
            'question' => ['en' => 'Q1'],
            'answer' => ['en' => 'A1'],
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this->getJson('/api/faqs');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonPath('0.question.en', 'Q1');
    }

    public function test_inactive_faqs_are_hidden(): void
    {
        Faq::create([
            'question' => ['en' => 'Hidden Q'],
            'answer' => ['en' => 'Hidden A'],
            'is_active' => false,
        ]);

        $response = $this->getJson('/api/faqs');

        $response->assertStatus(200)
            ->assertJsonCount(0);
    }
}
