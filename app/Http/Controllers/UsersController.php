<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersFromRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use App\Http\Controllers\MsgsController;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', [
            'except' => ['show', 'create', 'store', 'index', 'confirmEmail']
        ]);

        $this->middleware('guest', [
            'only' => ['create', 'store', 'confirmEmail']
        ]);
    }

    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function show(User $user)
    {
        $statuses = $user->statuses()->orderBy('created_at', 'desc')->paginate(15);
        return view('users.show', compact('user', 'statuses'));
    }

    public function store(UsersFromRequest $request)
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $msg = new MsgsController();
        $msg->sendEmail($user);
        session()->flash('success', '验证邮件已发送到您的注册邮箱，请注意查收。');
        return redirect('/');
    }

    /**
     * @param $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();
        $user->activation = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success', '恭喜你，激活成功。');
        return redirect()->route('users.show', $user->id);
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(User $user, Request $request)
    {
        $this->authorize('update', $user);
        $this->validate($request, [
            'name'     => 'required|max:60',
            'password' => 'nullable|confirmed|min:5'
        ]);

        $param = [];
        $param['name'] = $request->name;
        if ($request->password) {
            $param['password'] = bcrypt($request->password);
        }
        $user->update($param);

        session()->flash('success', '资料修改成功。');
        return redirect()->route('users.show', $user->id);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '删除成功。');
        return back();
    }

    public function followings(User $user)
    {
        $users = $user->followings()->paginate(20);
        $title = $user->name . '的粉丝';
        return view('users.show_follow', compact('users', 'title'));
    }

    public function followers(User $user)
    {
        $users = $user->followers()->paginate(20);
        $title = $user->name . '关注的人';
        return view('users.show_follow', compact('users', 'title'));
    }
}
