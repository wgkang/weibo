<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsersFromRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required|max:50',
            'email'=>'required|email|unique:users|max:225',
            'password'=>'required|confirmed|min:5',
            'captcha'=>'required|captcha'
        ];
    }

    public function messages()
    {
        return [
            'captcha.required'=>'验证码不能为空',
            'captcha.captcha'=>'验证码错误'
        ];
    }
}
