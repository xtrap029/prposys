<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Faq;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FaqsController extends Controller {
    
    public function index() {
        $categories = Faq::select('category')->groupBy('category')->orderBy('category', 'asc')->get();
        
        foreach ($categories as $key => $value) {
            $faqs = Faq::where('category', $value->category)->orderBy('title', 'asc')->get();
            $categories[$key]->faqs = $faqs;
        }

        return view('pages.resources.faq.index')->with([
            'categories' => $categories
        ]);
    }

    public function manage_index() {
        $faqs = Faq::orderBy('title', 'asc')->get();

        return view('pages.resources.faq.manageindex')->with([
            'faqs' => $faqs
        ]);
    }

    public function create() {
        $categories = Faq::select('category')->groupBy('category')->orderBy('category', 'asc')->pluck('category')->toArray();

        return view('pages.resources.faq.create')->with([
            'categories' => $categories
        ]);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'title' => ['required', 'min:3', Rule::unique('faqs')],
            'category' => ['required'],
            'description' => ['required'],
        ]);

        $data['owner_id'] = auth()->id();
        $data['updated_id'] = auth()->id();

        Faq::create($data);

        return redirect('/faqs-manage')->with('success', 'FAQ'.__('messages.create_success'));
    }

    public function edit(Faq $faq) {
        $categories = Faq::select('category')->groupBy('category')->orderBy('category', 'asc')->pluck('category')->toArray();

        return view('pages.resources.faq.edit')->with([
            'faq' => $faq,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, Faq $faq) {
        $data = $request->validate([
            'title' => ['required', 'min:3', Rule::unique('faqs')->ignore($faq->id)],
            'category' => ['required'],
            'description' => ['required'],
        ]);

        $data['updated_id'] = auth()->id();

        $faq->update($data);

        return redirect('/faqs-manage')->with('success', 'FAQ'.__('messages.edit_success'));
    }

    public function destroy(Faq $faq) {
        $faq->delete();

        return redirect('/faqs-manage')->with('success', 'FAQ'.__('messages.delete_success'));
    }
}
