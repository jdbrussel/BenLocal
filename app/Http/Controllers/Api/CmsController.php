<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Page;
use Illuminate\Http\Request;

class CmsController extends Controller
{
    public function getPage(string $slug)
    {
        $page = Page::where('slug', $slug)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        return response()->json([
            'slug' => $page->slug,
            'title' => $page->getTranslations('title'),
            'intro' => $page->getTranslations('intro'),
            'content' => $page->getTranslations('content'),
            'seo_title' => $page->getTranslations('seo_title'),
            'seo_description' => $page->getTranslations('seo_description'),
            'is_system_page' => $page->is_system_page,
            'published_at' => $page->published_at,
        ]);
    }

    public function getFaqs()
    {
        $faqs = Faq::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json($faqs->map(fn (Faq $faq) => [
            'id' => $faq->id,
            'question' => $faq->getTranslations('question'),
            'answer' => $faq->getTranslations('answer'),
            'category' => $faq->category,
        ]));
    }
}
