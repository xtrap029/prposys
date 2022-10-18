<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\User;
use App\File;
use App\UaLevel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FilesController extends Controller {
    
    public function index(Request $request) {
        $ua_level_id = User::where('id', auth()->id())->first()->ua_level_id;
        
        $files = File::where('ua_level_ids', 'LIKE', '%-'.$ua_level_id.'-%');

        if($request->s) {
            $search = $request->s;
            $files = $files->where(static function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $files = $files->orderBy('name', 'asc')->get();

        return view('pages.resources.file.index')->with([
            'files' => $files
        ]);
    }

    public function manage_index() {
        $files = File::orderBy('name', 'asc')->get();

        return view('pages.resources.file.manageindex')->with([
            'files' => $files
        ]);
    }

    public function create() {
        $ua_levels = UaLevel::orderBy('order', 'desc')->get();


        return view('pages.resources.file.create')->with([
            'ua_levels' => $ua_levels
        ]);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => ['required', 'min:3', Rule::unique('files')],
            'drive' => ['required'],
            'description' => ['required'],
            'ua_level_ids.*' => ['nullable'],
        ]);

        if ($data['ua_level_ids']) {
            foreach ($data['ua_level_ids'] as $key => $value) {
                $data['ua_level_ids'][$key] = '-'.$value.'-';
            }
        }

        $data['ua_level_ids'] = $data['ua_level_ids'] ? implode("", $data['ua_level_ids']) : "";
        $data['owner_id'] = auth()->id();
        $data['updated_id'] = auth()->id();

        File::create($data);

        return redirect('/files-manage')->with('success', 'File'.__('messages.create_success'));
    }

    public function edit(File $file) {
        $ua_levels = UaLevel::orderBy('order', 'desc')->get();

        $ua_level_ids = explode('--', $file->ua_level_ids);
        foreach ($ua_level_ids as $key => $value) {
            $ua_level_ids[$key] = str_replace('-', '', $value);
        }

        return view('pages.resources.file.edit')->with([
            'file' => $file,
            'ua_level_ids' => $ua_level_ids,
            'ua_levels' => $ua_levels
        ]);
    }

    public function update(Request $request, File $file) {
        $data = $request->validate([
            'name' => ['required', 'min:3', Rule::unique('files')->ignore($file->id)],
            'drive' => ['required'],
            'description' => ['required'],
            'ua_level_ids.*' => ['nullable'],
        ]);

        if ($data['ua_level_ids']) {
            foreach ($data['ua_level_ids'] as $key => $value) {
                $data['ua_level_ids'][$key] = '-'.$value.'-';
            }
        }

        $data['ua_level_ids'] = $data['ua_level_ids'] ? implode("", $data['ua_level_ids']) : "";
        $data['updated_id'] = auth()->id();

        $file->update($data);

        return redirect('/files-manage')->with('success', 'File'.__('messages.edit_success'));
    }

    public function destroy(File $file) {
        $file->delete();

        return redirect('/files-manage')->with('success', 'File'.__('messages.delete_success'));
    }
}
