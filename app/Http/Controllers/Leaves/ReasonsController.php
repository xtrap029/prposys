<?php

namespace App\Http\Controllers\Leaves;

use App\LeaveReason;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReasonsController extends Controller {

    public function index() {
        return view('pages.leaves.reason.index')->with([
            'reasons' => LeaveReason::orderBy('name', 'asc')->get()
        ]);
    }
    
    public function create() {
        return view('pages.leaves.reason.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => ['required', Rule::unique('leaves_reasons')->whereNull('deleted_at')],
            'color' => ['required']
        ]);
        $data['owner_id'] = auth()->id();
        $data['updated_id'] = auth()->id();

        LeaveReason::create($data);

        return redirect('/leaves-reason')->with('success', 'Leave Reason'.__('messages.create_success'));
    }

    public function edit(LeaveReason $leavesReason) {
        return view('pages.leaves.reason.edit')->with([
            'reason' => $leavesReason
        ]);
    }

    public function update(Request $request, LeaveReason $leavesReason) {
        $data = $request->validate([
            'name' => ['required', Rule::unique('leaves_reasons')->ignore($leavesReason->id)->whereNull('deleted_at')],
            'color' => ['required']
        ]);
        $data['updated_id'] = auth()->id();

        $leavesReason->update($data);

        return redirect('/leaves-reason')->with('success', 'Leave Reason'.__('messages.edit_success'));
    }

    public function destroy(LeaveReason $leavesReason) {
        $leavesReason->updated_id = auth()->id();
        $leavesReason->save();
        $leavesReason->delete();

        return redirect('/leaves-reason')->with('success', 'Leave Reason'.__('messages.delete_success'));
    }
}
