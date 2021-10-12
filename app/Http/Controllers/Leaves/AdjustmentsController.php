<?php

namespace App\Http\Controllers\Leaves;

use App\LeaveAdjustment;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdjustmentsController extends Controller {

    public function index() {
        $adjustments = LeaveAdjustment::orderBy('id', 'desc');

        if (isset($_GET['user_id']) && $_GET['user_id'] != "") {
            $adjustments = $adjustments->where('user_id', $_GET['user_id']);
        }       
        
        $adjustments = $adjustments->paginate(10);

        if (isset($_GET['user_id']) && $_GET['user_id'] != "") {
            $adjustments->appends(['user_id' => $_GET['user_id']]);
        }

        return view('pages.leaves.adjustment.index')->with([
            'adjustments' => $adjustments,
            'users' => User::orderBy('name', 'asc')->whereNotNull('role_id')->get(),
        ]);
    }
    
    public function create() {
        return view('pages.leaves.adjustment.create')->with([
            'users' => User::orderBy('name', 'asc')->whereNotNull('role_id')->get()
        ]);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'quantity' => ['required', 'numeric'],
            'remarks' => ['required']
        ]);
        $data['owner_id'] = auth()->id();
        $data['updated_id'] = auth()->id();

        LeaveAdjustment::create($data);

        return redirect('/leaves-adjustment')->with('success', 'Leave Adjustment'.__('messages.create_success'));
    }

    public function edit(LeaveAdjustment $leavesAdjustment) {
        return view('pages.leaves.adjustment.edit')->with([
            'adjustment' => $leavesAdjustment,
            'users' => User::orderBy('name', 'asc')->whereNotNull('role_id')->get()
        ]);
    }

    public function update(Request $request, LeaveAdjustment $leavesAdjustment) {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'quantity' => ['required', 'numeric'],
            'remarks' => ['required']
        ]);
        $data['updated_id'] = auth()->id();

        $leavesAdjustment->update($data);

        return redirect('/leaves-adjustment')->with('success', 'Leave Adjustment'.__('messages.edit_success'));
    }

    public function destroy(LeaveAdjustment $leavesAdjustment) {
        $leavesAdjustment->updated_id = auth()->id();
        $leavesAdjustment->save();
        $leavesAdjustment->delete();

        return redirect('/leaves-adjustment')->with('success', 'Leave Adjustment'.__('messages.delete_success'));
    }

    public function index_my() {
        return view('pages.leaves.adjustmentmy.index');
    }
}
