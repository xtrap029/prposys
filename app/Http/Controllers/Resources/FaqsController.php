<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Faq;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FaqsController extends Controller {
    
    public function index() {
        $faqs = Faq::orderBy('title', 'asc')->get();

        return view('pages.resources.faq.index')->with([
            'faqs' => $faqs
        ]);
    }

    public function manage_index() {
        $faqs = Faq::orderBy('title', 'asc')->get();

        return view('pages.resources.faq.manageindex')->with([
            'faqs' => $faqs
        ]);
    }
}
