<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersFromRequest;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
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
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password)
        ]);

        session()->flash('success','欢迎，您将在这里开启一段新的旅程');
        return redirect()->route('users.show',$user);
    }
}
