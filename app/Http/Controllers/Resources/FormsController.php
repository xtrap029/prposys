<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\User;
use App\Form;
use App\UaLevel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FormsController extends Controller {
    
    public function index(Request $request) {
        $ua_level_id = User::where('id', auth()->id())->first()->ua_level_id;
        $categories = Form::select('category')->groupBy('category')->orderBy('category', 'asc')->get();
        
        
        foreach ($categories as $key => $value) {
            $forms = Form::where('category', $value->category);

            if($request->s) {
                $search = $request->s;
                $forms = $forms->where(static function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            }

            $forms = $forms->orderBy('name', 'asc')->get();
            
            $visible_forms = [];
            foreach ($forms as $form) {
                if (in_array($ua_level_id, explode(',', $form->ua_level_ids))) {
                    $visible_forms[] = $form;
                }
            }

            $categories[$key]->forms = $visible_forms;
            $categories[$key]->random = Str::random(10);

        }

        return view('pages.resources.form.index')->with([
            'categories' => $categories
        ]);
    }

    public function manage_index() {
        $forms = Form::orderBy('name', 'asc')->get();

        return view('pages.resources.form.manageindex')->with([
            'forms' => $forms
        ]);
    }

    public function create() {
        $categories = Form::select('category')->groupBy('category')->orderBy('category', 'asc')->pluck('category')->toArray();
        $ua_levels = UaLevel::orderBy('order', 'desc')->get();


        return view('pages.resources.form.create')->with([
            'categories' => $categories,
            'ua_levels' => $ua_levels
        ]);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => ['required', 'min:3', Rule::unique('forms')],
            'category' => ['required'],
            'description' => ['required'],
            'ua_level_ids.*' => ['nullable'],
            'attachment' => ['required', 'mimes:jpeg,png,jpg,pdf,docx', 'max:15000'],
        ]);

        $data['ua_level_ids'] = $request->ua_level_ids ? implode(",", $request->ua_level_ids) : "";
        $data['attachment'] = basename($request->file('attachment')->store('public/attachments/form'));
        $data['owner_id'] = auth()->id();
        $data['updated_id'] = auth()->id();

        Form::create($data);

        return redirect('/forms-manage')->with('success', 'Form'.__('messages.create_success'));
    }

    public function edit(Form $form) {
        $categories = Form::select('category')->groupBy('category')->orderBy('category', 'asc')->pluck('category')->toArray();
        $ua_levels = UaLevel::orderBy('order', 'desc')->get();

        return view('pages.resources.form.edit')->with([
            'form' => $form,
            'categories' => $categories,
            'ua_levels' => $ua_levels
        ]);
    }

    public function update(Request $request, Form $form) {
        $data = $request->validate([
            'name' => ['required', 'min:3', Rule::unique('forms')->ignore($form->id)],
            'category' => ['required'],
            'description' => ['required'],
            'ua_level_ids.*' => ['nullable'],
            'attachment' => ['sometimes', 'mimes:jpeg,png,jpg,pdf,docx', 'max:15000'],
        ]);

        if ($request->file('attachment')) {
            Storage::delete('public/attachments/form/' . $form->attachment);
            $data['attachment'] = basename($request->file('attachment')->store('public/attachments/form'));
        }

        $data['ua_level_ids'] = $request->ua_level_ids ? implode(",", $request->ua_level_ids) : "";
        $data['updated_id'] = auth()->id();

        $form->update($data);

        return redirect('/forms-manage')->with('success', 'Form'.__('messages.edit_success'));
    }

    public function destroy(Form $form) {
        $form->delete();

        return redirect('/forms-manage')->with('success', 'Form'.__('messages.delete_success'));
    }
}
