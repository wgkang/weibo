<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersFromRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth',[
            'except' => ['create','store','index']
        ]);

        $this->middleware('guest',[
            'only' => ['create','store']
        ]);
    }

    public function index()
    {
        $users = User::all();
        return view('users.index',compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function store(UsersFromRequest $request)
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password)
        ]);

        Auth::login($user);
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程');
        return redirect()->route('users.show', $user);
    }

    public function edit(User $user)
    {
        $this->authorize('update',$user);
        return view('users.edit',compact('user'));
    }

    /**
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(User $user, Request $request)
    {
        $this->authorize('update',$user);
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
}
