<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class AttachmentsController extends Controller
{
    public function show($type, $filename)
    {
        $path = 'public/attachments/' . $type . '/' . $filename;

        if (!Storage::exists($path)) {
            abort(404);
        }

        return response()->file(Storage::path($path));
    }
}
