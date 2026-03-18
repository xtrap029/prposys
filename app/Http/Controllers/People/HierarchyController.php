<?php

namespace App\Http\Controllers\People;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Hierarchy;
use App\User;
use App\Helpers\UAHelper;

class HierarchyController extends Controller
{

    public function index()
    {
        $hierarchies = Hierarchy::with('user')->get();
        $can_manage = UAHelper::get()['peo_hierarchy_manage'] == config('global.is_yesno_id')[0];

        $nodes = $hierarchies->map(function ($h) {
            $user = $h->user;

            return array_filter([
                'id' => $h->user_id,
                'pid' => $h->parent_id,
                'name' => $user ? $user->name : 'Unknown',
                'title' => $user ? ($user->e_position ?? '') : '',
                'img' => ($user && $user->avatar) ? "/storage/public/images/users/{$user->avatar}" : null,
            ], function ($v) {
                return $v !== null;
            });
        })->values();

        return view('pages.people.hierarchy.index')->with([
            'can_manage' => $can_manage,
            'nodes' => $nodes,
        ]);
    }

    public function manage($parent_id = null)
    {
        if ($parent_id) {
            $hierarchies = Hierarchy::where('parent_id', $parent_id)->get();
            $hierarchy = Hierarchy::where('user_id', $parent_id)->first();
        } else {
            $hierarchies = Hierarchy::whereNull('parent_id')->get();
            $hierarchy = null;
        }

        $top_hierarchy = Hierarchy::whereNull('parent_id')->first();
        $users = User::where('id', '!=', $top_hierarchy->user_id)->orderBy('name', 'asc')->get();

        return view('pages.people.hierarchy.manage', compact([
            'hierarchies',
            'users',
            'hierarchy'
        ]));
    }

    public function store(Request $request, $parent_id = null)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        if (Hierarchy::where('user_id', $data['user_id'])->exists()) {
            return redirect()->route('people-hierarchy-manage', ['parent_id' => $parent_id])->with('error', 'User already in hierarchy');
        }

        if ($parent_id) {
            $data['parent_id'] = $parent_id;
        }

        Hierarchy::create($data);

        return redirect()->route('people-hierarchy-manage', ['parent_id' => $parent_id])->with('success', 'Hierarchy created successfully');
    }

    public function destroy($hierarchy_id)
    {
        $root = Hierarchy::find($hierarchy_id);
        if (!$root) {
            return redirect()->route('people-hierarchy-manage')->with('error', 'Hierarchy not found');
        }

        // NOTE: In this schema, parent_id references a user's id (not hierarchy.id).
        // So descendants are rows whose parent_id is any ancestor's user_id.
        $userIdsToDelete = [];
        $queue = [$root->user_id];

        // Prevent infinite loops if data is corrupted (cycles)
        $seen = [];
        while (!empty($queue)) {
            $currentUserId = array_shift($queue);
            if (isset($seen[$currentUserId])) {
                continue;
            }
            $seen[$currentUserId] = true;
            $userIdsToDelete[] = $currentUserId;

            $childUserIds = Hierarchy::where('parent_id', $currentUserId)->pluck('user_id')->all();
            foreach ($childUserIds as $childUserId) {
                if (!isset($seen[$childUserId])) {
                    $queue[] = $childUserId;
                }
            }
        }

        Hierarchy::whereIn('user_id', $userIdsToDelete)->delete();

        return redirect()
            ->route('people-hierarchy-manage', ['parent_id' => $root->parent_id])
            ->with('success', 'Hierarchy deleted successfully');
    }
}
