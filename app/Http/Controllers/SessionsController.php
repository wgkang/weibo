<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  Auth;

class SessionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest',[
            'only' => ['create']
        ]);
    }

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
            'password'=>'required',
        ]);
        unset($param['captcha']);
        if (Auth::attempt($param, $request->has('remember'))){
            if (Auth::user()->activation){
                session()->flash('success','欢迎回来');
                $fallback = route('users.show',Auth::user());
                return redirect()->intended($fallback);
            }else{
                Auth::logout();
                session()->flash('warning','您的账号未激活，请查看邮箱中的注册邮件进行激活。');
                return redirect('/');
            }

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
