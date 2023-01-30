<?php

namespace App\Http\Controllers\Travels;

use App\Http\Controllers\Controller;
use App\User;
use App\Travel;
use App\TravelsAttachment;
use App\Company;
use App\CompanyProject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class TravelsController extends Controller {
    
    public function index(Request $request) {
        $travels = new Travel;

        if (!empty($_GET['id'])
            || !empty($_GET['name_id'])
            || !empty($_GET['company_project_id'])
            || !empty($_GET['destination'])
            || !empty($_GET['date_from'])
            || !empty($_GET['date_to'])) {
                                    
            if ($_GET['id'] != "") $travels = $travels->where('id', $_GET['id']);
            if ($_GET['name_id'] != "") $travels = $travels->where('name_id', $_GET['name_id']);
            if ($_GET['company_project_id'] != "") $travels = $travels->where('company_project_id', $_GET['company_project_id']);
            if ($_GET['destination'] != "") $travels = $travels->where('destination', $_GET['destination']);            
            if ($_GET['date_from'] != "") $travels = $travels->whereDate('date_from', $_GET['date_from']);
            if ($_GET['date_to'] != "") $travels->whereDate('date_to', $_GET['date_to']);

            $travels = $travels->orderBy('id', 'desc')->paginate(10);

            $travels->appends(['id' => $_GET['id']]);
            $travels->appends(['name_id' => $_GET['name_id']]);
            $travels->appends(['company_project_id' => $_GET['company_project_id']]);
            $travels->appends(['destination' => $_GET['destination']]);
            $travels->appends(['date_from' => $_GET['date_from']]);
            $travels->appends(['date_to' => $_GET['date_to']]);
        } else {
            $travels = $travels->orderBy('id', 'desc')->paginate(10);
        }

        $users = User::where('ua_level_id', '!=', config('global.ua_inactive'))->orderBy('name', 'asc')->get();
        $users_inactive = User::where('ua_level_id', config('global.ua_inactive'))->orderBy('name', 'asc')->get();
        $projects = CompanyProject::orderBy('project', 'asc')->get();

        foreach ($travels as $key => $value) {

            $traveling_users = explode('--', $value->traveling_users);

            $travelers = [];
            foreach ($traveling_users as $key2 => $value2) {
                $travelers[] = str_replace('-', '', $value2);
            }

            $travels[$key]->travelers = User::select('name')->find($travelers);
        }
        
        return view('pages.travels.travels.index')->with([
            'travels' => $travels,
            'users' => $users,
            'users_inactive' => $users_inactive,
            'projects' => $projects,
        ]);
    }
    
    public function create() {
        $users = User::where('ua_level_id', '!=', config('global.ua_inactive'))->orderBy('name', 'asc')->get();
        $users_inactive = User::where('ua_level_id', config('global.ua_inactive'))->orderBy('name', 'asc')->get();
        $companies = Company::orderBy('name', 'asc')->get();

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
        
        return view('pages.travels.travels.create')->with([
            'users' => $users,
            'users_inactive' => $users_inactive,
            'companies' => $companies
        ]);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name_id' => ['required', 'exists:users,id'],
            'company_project_id' => ['required', 'exists:company_projects,id'],
            'date_from' => ['required', 'before:date_to'],
            'date_to' => ['required', 'after:date_from'],
            'destination' => ['required'],
            'traveling_users.*' => ['nullable'],
            'traveling_users_static' => ['required'],
        ]);
        if (!empty($data['traveling_users'])) {
            foreach ($data['traveling_users'] as $key => $value) {
                $data['traveling_users'][$key] = '-'.$value.'-';
            }
        }

        $data['traveling_users'] = !empty($data['traveling_users']) ? implode("", $data['traveling_users']) : "";
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

        return redirect('/travels')->with('success', 'Travel'.__('messages.create_success'));
    }

    public function edit(Travel $travel) {
        $users = User::where('ua_level_id', '!=', config('global.ua_inactive'))->orderBy('name', 'asc')->get();
        $users_inactive = User::where('ua_level_id', config('global.ua_inactive'))->orderBy('name', 'asc')->get();
        $companies = Company::orderBy('name', 'asc')->get();

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

        $traveling_users = explode('--', $travel->traveling_users);
        foreach ($traveling_users as $key => $value) {
            $traveling_users[$key] = str_replace('-', '', $value);
        }

        return view('pages.travels.travels.edit')->with([
            'users' => $users,
            'users_inactive' => $users_inactive,
            'companies' => $companies,
            'travel' => $travel,
            'traveling_users' => $traveling_users
        ]);
    }

    public function update(Request $request, Travel $travel) {
        $data = $request->validate([
            'name_id' => ['required', 'exists:users,id'],
            'company_project_id' => ['required', 'exists:company_projects,id'],
            'date_from' => ['required', 'before:date_to'],
            'date_to' => ['required', 'after:date_from'],
            'destination' => ['required'],
            'traveling_users.*' => ['nullable'],
            'traveling_users_static' => ['required'],
        ]);

        if ($data['traveling_users']) {
            foreach ($data['traveling_users'] as $key => $value) {
                $data['traveling_users'][$key] = '-'.$value.'-';
            }
        }

        $data['traveling_users'] = $data['traveling_users'] ? implode("", $data['traveling_users']) : "";
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

        $travel->update($data);

        return redirect('/travels')->with('success', 'Travel'.__('messages.edit_success'));
    }

    public function destroy(Travel $travel) {
        $travel->updated_id = auth()->id();
        $travel->save();
        $travel->delete();

        return redirect('/travels')->with('success', 'Travel'.__('messages.delete_success'));
    }
}
