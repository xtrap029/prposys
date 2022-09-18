<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Faq;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class FaqsController extends Controller {
    
    public function index(Request $request) {
        $categories = Faq::select('category')->groupBy('category')->orderBy('category', 'asc')->get();
        
        
        foreach ($categories as $key => $value) {
            $faqs = Faq::where('category', $value->category);

            if($request->s) {
                $search = $request->s;
                $faqs = $faqs->where(static function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            }

            $faqs = $faqs->orderBy('title', 'asc')->get();
            $categories[$key]->faqs = $faqs;
            $categories[$key]->random = Str::random(10);
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
