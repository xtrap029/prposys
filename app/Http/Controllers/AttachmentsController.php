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

        $transaction = Transaction::where('issue_slip', $filename)->first();

        $attachmentKey = $transaction
            ? AttachmentKey::where('transaction_id', $transaction->id)
                ->where('attachment_type', $type)
                ->first()
            : null;

        if (!$attachmentKey || $attachmentKey->key !== $request->key || $attachmentKey->isExpired()) {
            return back()->with('error', 'Invalid or expired key.');
        }

        session()->put('verified_attachment_' . $filename, true);

        return redirect()->route('attachments.show', ['type' => $type, 'filename' => $filename]);
    }
}
