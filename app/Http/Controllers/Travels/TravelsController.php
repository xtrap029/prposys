<?php

namespace App\Http\Controllers\Travels;

use App\Http\Controllers\Controller;
use App\User;
use App\Travel;
use App\TravelsPassenger;
use App\TravelsAttachment;
use App\TravelsRequestType;
use App\TravelStatus;
use App\TravelsFlight;
use App\TravelsHotel;
use App\Company;
use App\CompanyProject;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class TravelsController extends Controller {
    
    public function index(Request $request) {
        $travels = new Travel;

        if (!empty($_GET['id'])
            || !empty($_GET['company_project_id'])
            || !empty($_GET['destination'])
            || !empty($_GET['date_from'])
            || !empty($_GET['date_to'])) {
                                    
            if ($_GET['id'] != "") $travels = $travels->where('id', $_GET['id']);
            if ($_GET['company_project_id'] != "") $travels = $travels->where('company_project_id', $_GET['company_project_id']);
            if ($_GET['destination'] != "") $travels = $travels->where('destination', $_GET['destination']);            
            if ($_GET['date_from'] != "") $travels = $travels->whereDate('date_from', $_GET['date_from']);
            if ($_GET['date_to'] != "") $travels->whereDate('date_to', $_GET['date_to']);

            $travels = $travels->orderBy('id', 'desc')->paginate(10);

            $travels->appends(['id' => $_GET['id']]);
            $travels->appends(['company_project_id' => $_GET['company_project_id']]);
            $travels->appends(['destination' => $_GET['destination']]);
            $travels->appends(['date_from' => $_GET['date_from']]);
            $travels->appends(['date_to' => $_GET['date_to']]);
        } else {
            $travels = $travels->orderBy('id', 'desc')->paginate(10);
        }

        $projects = CompanyProject::orderBy('project', 'asc')->get();
        
        return view('pages.travels.travels.index')->with([
            'travels' => $travels,
            'projects' => $projects,
        ]);
    }

    public function show(Travel $travel) {
        $status_cancelled = TravelStatus::where('name', 'CANCELLED')->first();
        $status = TravelStatus::where('name', '!=' ,'CANCELLED')->orderBy('id', 'asc')->get();

        $logs = Activity::where('subject_id', $travel->id)
                ->where('subject_type', 'App\Travel')
                ->orderBy('id', 'desc')->paginate(15)->onEachSide(1);

        $perms['can_edit'] = $this->check_can_edit($travel->id);
        $perms['can_cancel'] = $this->check_can_cancel($travel->id);
        $perms['can_for_review'] = $this->check_can_for_review($travel->id);
        $perms['can_for_approval'] = $this->check_can_for_approval($travel->id);
        $perms['can_for_booking'] = $this->check_can_for_booking($travel->id);
        $perms['can_booked'] = $this->check_can_booked($travel->id);

        foreach ($travel->flights as $key => $value) {
            $travel->flights[$key]->total = $value->fee + $value->fee_car + $value->fee_baggage + $value->fee_land;
        }

        foreach ($travel->hotels as $key => $value) {
            $travel->hotels[$key]->total = $value->fee + $value->fee_car + $value->fee_land;
        }

        return view('pages.travels.travels.show')->with([
            'travel' => $travel,
            'cancelled_id' => $status_cancelled->id,
            'status' => $status,
            'status_cancelled' => $status_cancelled,
            'perms' => $perms,
            'logs' => $logs,
        ]);
    }
    
    public function create() {
        $users = User::where('ua_level_id', '!=', config('global.ua_inactive'))->orderBy('name', 'asc')->get();
        $users_inactive = User::where('ua_level_id', config('global.ua_inactive'))->orderBy('name', 'asc')->get();
        $companies = Company::orderBy('name', 'asc')->get();
        $request_types = TravelsRequestType::orderBy('id', 'desc')->get();

        $comp = [];
        foreach ($companies as $key => $value) {
            if ($value->companyProject->count() > 0) {
                $comp[$key]['name'] = $value->name;
    
                foreach ($value->companyProject as $key2 => $value2) {
                    $comp[$key]['projects'][$key2]['id'] = $value2->id;
                    $comp[$key]['projects'][$key2]['name'] = $value2->project;
                }
            }
        }
        $companies = $comp;

        $perms['can_add_options'] = $this->check_can_add_options();
        
        return view('pages.travels.travels.create')->with([
            'users' => $users,
            'users_inactive' => $users_inactive,
            'request_types' => $request_types,
            'companies' => $companies,
            'perms' => $perms,
        ]);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'company_project_id' => ['required', 'exists:company_projects,id'],
            'travels_request_type_id' => ['required', 'exists:travels_request_types,id'],
            'date_from' => ['required', 'before:date_to'],
            'date_to' => ['required', 'after:date_from'],
            'destination' => ['required'],
            'purpose' => ['required'],
            'traveling_users_static' => ['required'],
        ]);

        $data['owner_id'] = auth()->id();
        $data['updated_id'] = auth()->id();

        $travel = Travel::create($data);

        $data_attach = $request->validate([
            'file.*' => ['required', 'mimes:jpeg,png,jpg,pdf', 'max:6048'],
            'attachment_description.*' => ['required']
        ]);

        $attr_file['travel_id'] = $travel->id;
        $attr_file['owner_id'] = auth()->id();
        $attr_file['updated_id'] = auth()->id();

        if (isset($data_attach['attachment_description'])) {
            foreach ($data_attach['attachment_description'] as $key => $value) {
                $attr_file['description'] = $value;
                $attr_file['file'] = basename($request->file('file')[$key]->store('public/attachments/travel_attachment'));
                
                TravelsAttachment::create($attr_file);
            }
        }

        $data_passenger = $request->validate([
            'passenger_id.*' => ['nullable', 'exists:users,id'],
            'travel_no.*' => ['nullable']
        ]);

        if (count($data_passenger) > 0) {
            $attr_passenger['travel_id'] = $travel->id;
            $attr_passenger['owner_id'] = auth()->id();
            $attr_passenger['updated_id'] = auth()->id();

            foreach ($data_passenger['passenger_id'] as $key => $value) {
                $attr_passenger['user_id'] = $value;
                $attr_passenger['travel_no'] = $data_passenger['travel_no'][$key];
    
                TravelsPassenger::create($attr_passenger);
            }
        }

        $data_flights= $request->validate([
            'f_airline.*' => ['required'],
            'f_remarks.*' => ['required'],
            'f_in.*' => ['required', 'date_format:Y-m-d\TH:i'],
            'f_out.*' => ['required', 'date_format:Y-m-d\TH:i'],
            'f_airfare.*' => ['required', 'min:0'],
            'f_car.*' => ['required', 'min:0'],
            'f_baggage.*' => ['required', 'min:0'],
            'f_land.*' => ['required', 'min:0'],
        ]);

        $attr_flights['travel_id'] = $travel->id;
        $attr_flights['owner_id'] = auth()->id();
        $attr_flights['updated_id'] = auth()->id();

        if (isset($data_flights['f_airline'])) {
            foreach ($data_flights['f_airline'] as $key => $value) {
                $attr_flights['name'] = $data_flights['f_airline'][$key];
                $attr_flights['remarks'] = $data_flights['f_remarks'][$key];
                $attr_flights['time_in'] = $data_flights['f_in'][$key];
                $attr_flights['time_out'] = $data_flights['f_out'][$key];
                $attr_flights['fee'] = $data_flights['f_airfare'][$key];
                $attr_flights['fee_car'] = $data_flights['f_car'][$key];
                $attr_flights['fee_baggage'] = $data_flights['f_baggage'][$key];
                $attr_flights['fee_land'] = $data_flights['f_land'][$key];
                
                TravelsFlight::create($attr_flights);
            }
        }

        $data_hotels= $request->validate([
            'h_name.*' => ['required'],
            'h_remarks.*' => ['required'],
            'h_rate.*' => ['required', 'min:0'],
            'h_car.*' => ['required', 'min:0'],
            'h_land.*' => ['required', 'min:0'],
        ]);

        $attr_hotels['travel_id'] = $travel->id;
        $attr_hotels['owner_id'] = auth()->id();
        $attr_hotels['updated_id'] = auth()->id();

        if (isset($data_hotels['h_name'])) {
            foreach ($data_hotels['h_name'] as $key => $value) {
                $attr_hotels['name'] = $data_hotels['h_name'][$key];
                $attr_hotels['remarks'] = $data_hotels['h_remarks'][$key];
                $attr_hotels['fee'] = $data_hotels['h_rate'][$key];
                $attr_hotels['fee_car'] = $data_hotels['h_car'][$key];
                $attr_hotels['fee_land'] = $data_hotels['h_land'][$key];
                
                TravelsHotel::create($attr_hotels);
            }
        }

        return redirect('/travels/view/'.$travel->id)->with('success', 'Travel'.__('messages.create_success'));
    }

    public function edit(Travel $travel) {
        if (!$this->check_can_edit($travel->id)) {
            return back()->with('error', __('messages.cant_edit'));
        }

        $users = User::where('ua_level_id', '!=', config('global.ua_inactive'))->orderBy('name', 'asc')->get();
        $users_inactive = User::where('ua_level_id', config('global.ua_inactive'))->orderBy('name', 'asc')->get();
        $companies = Company::orderBy('name', 'asc')->get();
        $request_types = TravelsRequestType::orderBy('id', 'desc')->get();

        $comp = [];
        foreach ($companies as $key => $value) {
            if ($value->companyProject->count() > 0) {
                $comp[$key]['name'] = $value->name;
    
                foreach ($value->companyProject as $key2 => $value2) {
                    $comp[$key]['projects'][$key2]['id'] = $value2->id;
                    $comp[$key]['projects'][$key2]['name'] = $value2->project;
                }
            }
        }
        $companies = $comp;

        $perms['can_add_options'] = $this->check_can_add_options();

        return view('pages.travels.travels.edit')->with([
            'users' => $users,
            'users_inactive' => $users_inactive,
            'companies' => $companies,
            'request_types' => $request_types,
            'travel' => $travel,
            'perms' => $perms,
        ]);
    }

    public function update(Request $request, Travel $travel) {
        if (!$this->check_can_edit($travel->id)) {
            return redirect('/travels/view/'.$travel->id)->with('error', __('messages.cant_edit'));
        }

        $data = $request->validate([
            'company_project_id' => ['required', 'exists:company_projects,id'],
            'travels_request_type_id' => ['required', 'exists:travels_request_types,id'],
            'date_from' => ['required', 'before:date_to'],
            'date_to' => ['required', 'after:date_from'],
            'destination' => ['required'],
            'purpose' => ['required'],
            'traveling_users_static' => ['required'],
        ]);

        $data['updated_id'] = auth()->id();

        $attach_travel = $request->validate([
            'file.*' => ['mimes:jpeg,png,jpg,pdf', 'max:6048'],
            'attachment_description_old.*' => ['required'],
            'attachment_description.*' => ['sometimes', 'required'],
            'attachment_id_old.*' => ['required']
        ]);

        $desc_key = 0;
        $attach_travel['attachment_id_old'] = isset($attach_travel['attachment_id_old']) ? $attach_travel['attachment_id_old'] : [];
        foreach ($travel->attachments as $key => $value) {
            $travel_attachment = TravelsAttachment::find($value->id);

            // check if item is retained
            if (in_array($value->id, $attach_travel['attachment_id_old'])) {
                // check if item is replaced
                if (!empty($request->file('file_old')) && array_key_exists($key, $request->file('file_old'))) {
                    // item is replaced
                    $travel_attachment->file = basename($request->file('file_old')[$key]->store('public/attachments/travel_attachment'));        
                    $travel_attachment->updated_id = auth()->id();
                }

                // replace description
                $travel_attachment->description = $attach_travel['attachment_description_old'][$desc_key];
                
                // store changes
                $travel_attachment->save();
                $desc_key++;
            } else {
                // the item is deleted
                $travel_attachment->delete();
            }
        }

        $attr_file['travel_id'] = $travel->id;
        $attr_file['owner_id'] = auth()->id();
        $attr_file['updated_id'] = auth()->id();

        if (array_key_exists('attachment_description', $attach_travel)) {
            foreach ($attach_travel['attachment_description'] as $key => $value) {
                $attr_file['description'] = $value;
                $attr_file['file'] = basename($request->file('file')[$key]->store('public/attachments/travel_attachment'));
                TravelsAttachment::create($attr_file);
            }
        }

        $data_passenger = $request->validate([
            'passenger_id.*' => ['nullable', 'exists:users,id'],
            'travel_no.*' => ['nullable']
        ]);

        TravelsPassenger::where('travel_id', $travel->id)->delete();

        if (count($data_passenger) > 0) {
            $attr_passenger['travel_id'] = $travel->id;
            $attr_passenger['owner_id'] = auth()->id();
            $attr_passenger['updated_id'] = auth()->id();

            foreach ($data_passenger['passenger_id'] as $key => $value) {
                $attr_passenger['user_id'] = $value;
                $attr_passenger['travel_no'] = $data_passenger['travel_no'][$key];
    
                TravelsPassenger::create($attr_passenger);
            }
        }

        $data_flights= $request->validate([
            'f_airline.*' => ['required'],
            'f_remarks.*' => ['required'],
            'f_in.*' => ['required', 'date_format:Y-m-d\TH:i'],
            'f_out.*' => ['required', 'date_format:Y-m-d\TH:i'],
            'f_airfare.*' => ['required', 'min:0'],
            'f_car.*' => ['required', 'min:0'],
            'f_baggage.*' => ['required', 'min:0'],
            'f_land.*' => ['required', 'min:0'],
        ]);

        $attr_flights['travel_id'] = $travel->id;
        $attr_flights['owner_id'] = auth()->id();
        $attr_flights['updated_id'] = auth()->id();

        TravelsFlight::where('travel_id', $travel->id)->delete();

        if (isset($data_flights['f_airline'])) {
            foreach ($data_flights['f_airline'] as $key => $value) {
                $attr_flights['name'] = $data_flights['f_airline'][$key];
                $attr_flights['remarks'] = $data_flights['f_remarks'][$key];
                $attr_flights['time_in'] = $data_flights['f_in'][$key];
                $attr_flights['time_out'] = $data_flights['f_out'][$key];
                $attr_flights['fee'] = $data_flights['f_airfare'][$key];
                $attr_flights['fee_car'] = $data_flights['f_car'][$key];
                $attr_flights['fee_baggage'] = $data_flights['f_baggage'][$key];
                $attr_flights['fee_land'] = $data_flights['f_land'][$key];
                
                TravelsFlight::create($attr_flights);
            }
        }

        $data_hotels= $request->validate([
            'h_name.*' => ['required'],
            'h_remarks.*' => ['required'],
            'h_rate.*' => ['required', 'min:0'],
            'h_car.*' => ['required', 'min:0'],
            'h_land.*' => ['required', 'min:0'],
        ]);

        $attr_hotels['travel_id'] = $travel->id;
        $attr_hotels['owner_id'] = auth()->id();
        $attr_hotels['updated_id'] = auth()->id();

        TravelsHotel::where('travel_id', $travel->id)->delete();

        if (isset($data_hotels['h_name'])) {
            foreach ($data_hotels['h_name'] as $key => $value) {
                $attr_hotels['name'] = $data_hotels['h_name'][$key];
                $attr_hotels['remarks'] = $data_hotels['h_remarks'][$key];
                $attr_hotels['fee'] = $data_hotels['h_rate'][$key];
                $attr_hotels['fee_car'] = $data_hotels['h_car'][$key];
                $attr_hotels['fee_land'] = $data_hotels['h_land'][$key];
                
                TravelsHotel::create($attr_hotels);
            }
        }

        $travel->update($data);

        return redirect('/travels/view/'.$travel->id)->with('success', 'Travel'.__('messages.edit_success'));
    }

    public function destroy(Travel $travel) {
        $travel->updated_id = auth()->id();
        $travel->save();
        $travel->delete();

        return redirect('/travels')->with('success', 'Travel'.__('messages.delete_success'));
    }

    public function cancel(Request $request, Travel $travel) {
        if (!$this->check_can_cancel($travel->id)) {
            return redirect('/travels/view/'.$travel->id)->with('error', __('messages.cant_cancel'));
        }
        
        $data = $request->validate([
            'cancellation_reason' => ['required']
        ]);
        
        $data['cancellation_number'] = rand(100000000, 999999999);
        $data['status_id'] = config('global.travel_status_id_cancelled');
        $data['updated_id'] = auth()->id();
        $travel->update($data);
        return back()->with('success', 'Travel'.__('messages.cancel_success'));
    }

    public function for_review(Travel $travel) {
        if (!$this->check_can_for_review($travel->id)) {
            return redirect('/travels/view/'.$travel->id)->with('error', __('messages.cant_edit'));
        }

        $data['status_id'] = config('global.travel_status_id_for_review');
        $data['updated_id'] = auth()->id();
        $travel->update($data);
        return back()->with('success', 'Travel'.__('messages.edit_success'));
    }

    public function for_approval(Travel $travel) {
        if (!$this->check_can_for_approval($travel->id)) {
            return redirect('/travels/view/'.$travel->id)->with('error', __('messages.cant_edit'));
        }

        $data['status_id'] = config('global.travel_status_id_for_approval');
        $data['updated_id'] = auth()->id();
        $travel->update($data);
        return back()->with('success', 'Travel'.__('messages.edit_success'));
    }

    public function for_booking(Request $request, Travel $travel) {
        if (!$this->check_can_for_booking($travel->id)) {
            return redirect('/travels/view/'.$travel->id)->with('error', __('messages.cant_edit'));
        }

        $validation = [];
        if ($travel->travels_request_type_id != 2) $validation['selected_flight'] = ['required', 'exists:travels_flights,id'];
        if ($travel->travels_request_type_id != 1) $validation['selected_hotel'] = ['required', 'exists:travels_hotels,id'];

        $selected = $request->validate($validation);

        if ($travel->travels_request_type_id != 2) {
            TravelsFlight::where('id', $selected['selected_flight'])->update(['is_selected' => 1]);
        }

        if ($travel->travels_request_type_id != 1) {
            TravelsHotel::where('id', $selected['selected_hotel'])->update(['is_selected' => 1]);
        }

        $data = [];
        $data['status_id'] = config('global.travel_status_id_for_booking');
        $data['updated_id'] = auth()->id();
        $travel->update($data);
        return back()->with('success', 'Travel'.__('messages.edit_success'));
    }

    public function booked(Request $request, Travel $travel) {
        if (!$this->check_can_booked($travel->id)) {
            return redirect('/travels/view/'.$travel->id)->with('error', __('messages.cant_edit'));
        }

        $data_attach = $request->validate([
            'file.*' => ['required', 'mimes:jpeg,png,jpg,pdf', 'max:6048'],
            'attachment_description.*' => ['required'],
            'type' => ['required']
        ]);

        $attr_file['travel_id'] = $travel->id;
        $attr_file['owner_id'] = auth()->id();
        $attr_file['updated_id'] = auth()->id();

        if (isset($data_attach['attachment_description'])) {
            foreach ($data_attach['attachment_description'] as $key => $value) {
                $attr_file['description'] = $value;
                $attr_file['type'] = $data_attach['type'][$key];
                $attr_file['file'] = basename($request->file('file')[$key]->store('public/attachments/travel_attachment'));
                
                TravelsAttachment::create($attr_file);
            }
        }

        $data['status_id'] = config('global.travel_status_id_booked');
        $data['updated_id'] = auth()->id();
        $travel->update($data);
        return back()->with('success', 'Travel'.__('messages.edit_success'));
    }

    private function check_can_add_options($user = '') {
        $can_add_options = true;

        if (!$user) {
            $user = auth()->id();
        }

        $user = User::where('id', $user)->first();

        if (!in_array(config('global.travel_role_id_admin'), explode(',', $user->travel_roles))) {
            $can_add_options = false;
        }

        return $can_add_options;
    }

    private function check_can_edit($travel, $user = '') {
        $can_edit = true;

        if (!$user) {
            $user = auth()->id();
        }

        $user = User::where('id', $user)->first();

        $travel = Travel::where('id', $travel)->first();

        // check if generated
        if ($travel->status_id == config('global.travel_status_id_generated')) {
            if (
                // check if not owner and not admin
                ($user->id != $travel->owner_id && !in_array(config('global.travel_role_id_admin'), explode(',', $user->travel_roles)))
            ) {
                $can_edit = false;
            }
        } else {
            $can_edit = false;
        }

        return $can_edit;
    }

    private function check_can_cancel($travel, $user = '') {
        $can_cancel = true;

        if (!$user) {
            $user = auth()->id();
        }

        $user = User::where('id', $user)->first();

        $travel = Travel::where('id', $travel)->first();

        // check if not booked or cancelled
        if (!in_array($travel->status_id, [config('global.travel_status_id_booked'), config('global.travel_status_id_cancelled')])) {
            if (
                // check if not owner and does not have travel roles
                (
                    $user->id != $travel->owner_id
                    && count(array_intersect(explode(',', $user->travel_roles), [
                        config('global.travel_role_id_admin'), config('global.travel_role_id_reviewer'),
                        config('global.travel_role_id_approver'), config('global.travel_role_id_booker'),
                    ])) == 0
                )
            ) {
                $can_cancel = false;
            }
        } else {
            $can_cancel = false;
        }
        
        return $can_cancel;
    }

    private function check_can_for_review($travel, $user = '') {
        $can_for_review = true;

        if (!$user) {
            $user = auth()->id();
        }

        $user = User::where('id', $user)->first();

        $travel = Travel::where('id', $travel)->first();

        // check if flight / hotel exists
        if ($travel->travels_request_type_id != 2) {
            if (TravelsFlight::where('travel_id', $travel->id)->get()->count() == 0) $can_for_review = false;
        }
        if ($travel->travels_request_type_id != 1) {
            if (TravelsHotel::where('travel_id', $travel->id)->get()->count() == 0) $can_for_review = false;
        }

        // check if generated
        if ($travel->status_id == config('global.travel_status_id_generated')) {
            if (
                // check if not admin
                (!in_array(config('global.travel_role_id_admin'), explode(',', $user->travel_roles)))
            ) {
                $can_for_review = false;
            }
        } else {
            $can_for_review = false;
        }

        return $can_for_review;
    }

    private function check_can_for_approval($travel, $user = '') {
        $can_for_approval = true;

        if (!$user) {
            $user = auth()->id();
        }

        $user = User::where('id', $user)->first();

        $travel = Travel::where('id', $travel)->first();

        // check if for review
        if ($travel->status_id == config('global.travel_status_id_for_review')) {
            if (
                // check if not admin 2
                (!in_array(config('global.travel_role_id_reviewer'), explode(',', $user->travel_roles)))
            ) {
                $can_for_approval = false;
            }
        } else {
            $can_for_approval = false;
        }

        return $can_for_approval;
    }

    private function check_can_for_booking($travel, $user = '') {
        $can_for_booking = true;

        if (!$user) {
            $user = auth()->id();
        }

        $user = User::where('id', $user)->first();

        $travel = Travel::where('id', $travel)->first();

        // check if for approval
        if ($travel->status_id == config('global.travel_status_id_for_approval')) {
            if (
                // check if not admin 3
                (!in_array(config('global.travel_role_id_approver'), explode(',', $user->travel_roles)))
            ) {
                $can_for_booking = false;
            }
        } else {
            $can_for_booking = false;
        }

        return $can_for_booking;
    }

    private function check_can_booked($travel, $user = '') {
        $can_booked = true;

        if (!$user) {
            $user = auth()->id();
        }

        $user = User::where('id', $user)->first();

        $travel = Travel::where('id', $travel)->first();

        // check if for booking
        if ($travel->status_id == config('global.travel_status_id_for_booking')) {
            if (
                // check if not admin 4
                (!in_array(config('global.travel_role_id_booker'), explode(',', $user->travel_roles)))
            ) {
                $can_booked = false;
            }
        } else {
            $can_booked = false;
        }

        return $can_booked;
    }
}
