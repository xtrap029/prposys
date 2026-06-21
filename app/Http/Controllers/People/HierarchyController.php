<?php

namespace App\Http\Controllers\People;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Hierarchy;
use App\User;
use App\Company;
use App\Helpers\UAHelper;

class HierarchyController extends Controller
{

    public function index(Request $request)
    {
        $user = User::find(auth()->id());
        $userCompanyIds = array_filter(explode(',', $user->companies));
        $companies = Company::whereIn('id', $userCompanyIds)->orderBy('name')->get();

        $company_id = $request->query('company_id');
        $can_manage = UAHelper::get()['peo_hierarchy_manage'] == config('global.is_yesno_id')[0];

        $nodes = collect();
        if ($company_id && $companies->contains('id', $company_id)) {
            $hierarchies = Hierarchy::with('user')->where('company_id', $company_id)->get();
            $nodes = $hierarchies->map(function ($h) {
                $user = $h->user;
                return array_filter([
                    'id' => $h->user_id,
                    'pid' => $h->parent_id,
                    'name' => $user ? $user->name : 'Unknown',
                    'title' => $user ? ($user->e_position ?? '') : '',
                    'img' => ($user && $user->avatar) ? "/storage/public/images/users/{$user->avatar}" : null,
                ], fn($v) => $v !== null);
            })->values();
        }

        return view('pages.people.hierarchy.index')->with([
            'can_manage' => $can_manage,
            'nodes' => $nodes,
            'companies' => $companies,
            'company_id' => $company_id,
        ]);
    }

    public function manage(Request $request, $parent_id = null)
    {
        $company_id = $request->query('company_id');

        if ($parent_id) {
            $hierarchies = Hierarchy::where('parent_id', $parent_id)->where('company_id', $company_id)->get();
            $hierarchy = Hierarchy::where('user_id', $parent_id)->where('company_id', $company_id)->first();
        } else {
            $hierarchies = Hierarchy::whereNull('parent_id')->where('company_id', $company_id)->get();
            $hierarchy = null;
        }

        $top_hierarchy = Hierarchy::whereNull('parent_id')->where('company_id', $company_id)->first();
        $excludeId = $top_hierarchy ? $top_hierarchy->user_id : null;
        $users = User::when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->where('ua_level_id', '!=', config('global.ua_inactive'))
            ->orderBy('name', 'asc')->get();

        return view('pages.people.hierarchy.manage', compact([
            'hierarchies',
            'users',
            'hierarchy',
            'company_id',
        ]));
    }

    public function store(Request $request, $parent_id = null)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'company_id' => ['required', 'exists:companies,id'],
        ]);

        if (Hierarchy::where('user_id', $data['user_id'])->where('company_id', $data['company_id'])->exists()) {
            return redirect()->route('people-hierarchy-manage', ['parent_id' => $parent_id, 'company_id' => $data['company_id']])->with('error', 'User already in hierarchy');
        }

        if ($parent_id) {
            $data['parent_id'] = $parent_id;
        }

        Hierarchy::create($data);

        return redirect()->route('people-hierarchy-manage', ['parent_id' => $parent_id, 'company_id' => $data['company_id']])->with('success', 'Hierarchy created successfully');
    }

    public function update(Request $request, $hierarchy_id)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $item = Hierarchy::findOrFail($hierarchy_id);
        $company_id = $item->company_id;

        if (Hierarchy::where('user_id', $data['user_id'])->where('company_id', $company_id)->where('id', '!=', $hierarchy_id)->exists()) {
            return redirect()->route('people-hierarchy-manage', ['parent_id' => $item->parent_id, 'company_id' => $company_id])->with('error', 'User already in hierarchy');
        }

        $item->update(['user_id' => $data['user_id']]);

        return redirect()->route('people-hierarchy-manage', ['parent_id' => $item->parent_id, 'company_id' => $company_id])->with('success', 'User updated successfully');
    }

    public function destroy($hierarchy_id)
    {
        $root = Hierarchy::find($hierarchy_id);
        if (!$root) {
            return redirect()->route('people-hierarchy-manage')->with('error', 'Hierarchy not found');
        }

        $company_id = $root->company_id;

        $userIdsToDelete = [];
        $queue = [$root->user_id];
        $seen = [];

        while (!empty($queue)) {
            $currentUserId = array_shift($queue);
            if (isset($seen[$currentUserId])) continue;
            $seen[$currentUserId] = true;
            $userIdsToDelete[] = $currentUserId;

            $childUserIds = Hierarchy::where('parent_id', $currentUserId)->where('company_id', $company_id)->pluck('user_id')->all();
            foreach ($childUserIds as $childUserId) {
                if (!isset($seen[$childUserId])) $queue[] = $childUserId;
            }
        }

        Hierarchy::whereIn('user_id', $userIdsToDelete)->where('company_id', $company_id)->delete();

        return redirect()
            ->route('people-hierarchy-manage', ['parent_id' => $root->parent_id, 'company_id' => $company_id])
            ->with('success', 'Hierarchy deleted successfully');
    }
}
