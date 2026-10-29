<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Setting;

class PageController extends Controller
{
    public function faq()
    {
        $faqs = Faq::where('is_active', true)->orderBy('sort_order')->get()->groupBy('category');

        return view('pages.faq', compact('faqs'));
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function about()
    {
        $content = Setting::where('key', 'about_content')->value('value');

        return view('pages.about', compact('content'));
    }
}
