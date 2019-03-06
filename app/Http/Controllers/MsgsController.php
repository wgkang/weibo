<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;

class MsgsController extends Controller
{
    public function sendEmail($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        $to   = $user->email;
        $subject = "感谢注册，请确认你的邮件。";

        Mail::send($view, $data, function($message) use ($to, $subject){
            $message->to($to)->subject($subject);
        });
    }
}
