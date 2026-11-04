<?php

namespace App\Http\Controllers;

use App\AttachmentKey;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttachmentsController extends Controller
{
    public function show($type, $filename)
    {
        $path = 'public/attachments/' . $type . '/' . $filename;

        if (!Storage::exists($path)) {
            abort(404);
        }

        if ($type !== 'issue_slip') {
            if (!Auth::check()) {
                return redirect()->route('login');
            }

            return response()->file(Storage::path($path));
        }

        if (Auth::check() || session()->get('verified_attachment_' . $filename)) {
            return response()->file(Storage::path($path));
        }

        return view('pages.attachments.verify-key', [
            'type' => $type,
            'filename' => $filename,
        ]);
    }

    public function verify(Request $request, $type, $filename)
    {
        $request->validate(['key' => ['required', 'string']]);

        // an issue slip can be shared (e.g. auto-generated PO and its source PR), each with its own key
        $transaction_ids = Transaction::where('issue_slip', $filename)->pluck('id');

        // compare in PHP, the column collation is case-insensitive
        $attachmentKey = AttachmentKey::whereIn('transaction_id', $transaction_ids)
            ->where('attachment_type', $type)
            ->get()
            ->first(function ($attachmentKey) use ($request) {
                return hash_equals($attachmentKey->key, $request->key);
            });

        if (!$attachmentKey || $attachmentKey->isExpired()) {
            return back()->with('error', 'Invalid or expired key.');
        }

        session()->put('verified_attachment_' . $filename, true);

        return redirect()->route('attachments.show', ['type' => $type, 'filename' => $filename]);
    }
}
