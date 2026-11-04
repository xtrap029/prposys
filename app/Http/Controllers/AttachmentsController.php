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

        // keep lookups inside the attachment folders
        if (str_contains($type, '..') || str_contains($filename, '..') || !Storage::exists($path)) {
            abort(404);
        }

        if ($type !== 'issue_slip') {
            if (!$this->isActiveUser()) {
                return redirect()->route('login');
            }

            return response()->file(Storage::path($path));
        }

        if ($this->isActiveUser() || $this->isVerified($filename)) {
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
        $transaction_ids = Transaction::where('issue_slip', $filename)
            ->whereNotIn('status_id', config('global.cancelled'))
            ->pluck('id');

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

        // remember which key was used, so access ends when that key expires or is reset
        session()->put('verified_attachment_' . $filename, [
            'id' => $attachmentKey->id,
            'key' => $attachmentKey->key,
        ]);

        return redirect()->route('attachments.show', ['type' => $type, 'filename' => $filename]);
    }

    private function isVerified($filename)
    {
        $verified = session()->get('verified_attachment_' . $filename);

        if (!is_array($verified)) {
            return false;
        }

        $attachmentKey = AttachmentKey::find($verified['id']);

        // the key must still be current, and its transaction must still use this file and not be cancelled
        if (
            !$attachmentKey
            || !hash_equals($attachmentKey->key, $verified['key'])
            || $attachmentKey->isExpired()
            || !$attachmentKey->transaction
            || $attachmentKey->transaction->issue_slip !== $filename
            || in_array($attachmentKey->transaction->status_id, config('global.cancelled'))
        ) {
            session()->forget('verified_attachment_' . $filename);

            return false;
        }

        return true;
    }

    private function isActiveUser()
    {
        return Auth::check() && Auth::user()->ua_level_id != config('global.ua_inactive');
    }
}
