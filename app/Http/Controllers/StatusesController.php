<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Status;

class StatusesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|max:140',
        ]);

        Auth::User()->statuses()->create([
            'content' => $request['content']
        ]);

        session()->flash('success', '发布成功。');
        return redirect()->back();
    }

    /**
     * @param Status $status
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(Status $status)
    {
        $this->authorize('destroy', $status);
        $status->delete();
        session()->flash('success', '删除成功。');
        return redirect()->back();
    }
}
