<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  Auth;

class SessionsController extends Controller
{
    public function create()
    {
        return view('sessions.create');
    }

    /**
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $param = $this->validate($request,[
            'email'=>'required|email|max:255',
            'password'=>'required'
        ]);

        if (Auth::attempt($param, $request->has('remember'))){
            session()->flash('success','欢迎回来');
            return redirect()->route('users.show',[Auth::user()]);
        }else{
            session()->flash('danger','很抱歉，邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }
    }

    public function destroy()
    {
        Auth::logout();
        session()->flash('success','您已退出登录。');
        return redirect('/');
    }
}
