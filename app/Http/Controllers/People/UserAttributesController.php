<?php

namespace App\Http\Controllers\People;

use App\UserAttribute;
use App\Settings;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class UserAttributesController extends Controller {

    public function index() {
        $user_attributes = UserAttribute::orderBy('order', 'asc')->get();
        return view('pages.people.userattribute.index')->with([
            'user_attributes' => $user_attributes
        ]);
    }

    public function create() {
        return view('pages.people.userattribute.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => ['required', Rule::unique('user_attributes')->whereNull('deleted_at')],
            'order' => ['required', 'integer'],
        ]);
        $data['owner_id'] = auth()->id();
        $data['updated_id'] = auth()->id();

        UserAttribute::create($data);

        return redirect('/user-attribute')->with('success', 'User Attribute'.__('messages.create_success'));
    }

    public function edit(UserAttribute $user_attribute) {
        return view('pages.people.userattribute.edit')->with([
            'user_attribute' => $user_attribute
        ]);
    }

    public function update(Request $request, UserAttribute $user_attribute) {
        $data = $request->validate([
            'name' => ['required', Rule::unique('user_attributes')->ignore($user_attribute->id)->whereNull('deleted_at')],
            'order' => ['required', 'integer'],
        ]);

        $data['updated_id'] = auth()->id();

        $user_attribute->update($data);

        return redirect('/user-attribute')->with('success', 'User Attribute'.__('messages.edit_success'));
    }

    public function destroy(UserAttribute $user_attribute) {
        $user_attribute->updated_id = auth()->id();
        $user_attribute->save();
        $user_attribute->delete();

        return redirect('/user-attribute')->with('success', 'User Attribute'.__('messages.delete_success'));
    }
}
