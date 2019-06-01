<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Group;
use App\UserGroupRelation;
use App\Http\Requests\StoreGroup;

class GroupsController extends Controller
{

    /**
     * Constructor
     */
    public function __construct()
    {
        // 各アクションの前に実行させるミドルウェア
        $this->middleware('auth');
    }

    /**
     * グループ一覧の表示
     */
    public function index()
    {
        $user = Auth::user();
        $groups = UserGroupRelation::where('user_id', $user->id)
            ->leftJoin('users', 'user_group_relations.user_id', '=', 'users.id')
            ->leftJoin('groups', 'user_group_relations.group_id', '=', 'groups.id')
            ->orderBy('groups.account')
            ->paginate(5);
        return view('groups.index', ['profile' => $user, 'groups' => $groups]);
    }

    /**
     * 新規グループ作成
     */
    public function create()
    {
        $user = Auth::user();
        return view('groups.create', ['profile' => $user]);
    }

    /**
     * 新規グループ作成: 実行
     */
    public function store(StoreGroup $request)
    {
        $user = Auth::user();
        $group = new Group;
        $rules = $request->rules();
        $request->validate([
            'name' => $rules['name'],
            'account' => $rules['account'],
            'description' => $rules['description']
        ]);
        $group->name = $request->name;
        $group->account = $request->account;
        $group->description = $request->description;
        $group->creator_user_id = $user->id;
        $group->save();

        $userGroupRelation = new UserGroupRelation;
        $userGroupRelation->user_id = $user->id;
        $userGroupRelation->group_id = $group->id;
        $userGroupRelation->role = 'owner';
        $userGroupRelation->save();

        return redirect('settings/groups')->with('flash_message', __('Created new group.'));
    }

}
