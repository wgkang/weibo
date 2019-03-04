<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionsController extends Controller
{
    public function create()
    {
        return view('sessions.create');
    }

    public function store(Request $request)
    {
        $param = $this->validate($request,[
            'email'=>'requried|email|max:255',
            'password'=>'requried'
        ]);

    }
}
