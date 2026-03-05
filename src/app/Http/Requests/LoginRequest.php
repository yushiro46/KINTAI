<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LoginRequest extends FormRequest
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
            'email' => 'required',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'メールアドレスを入力してください',
            'password.required' => 'パスワードを入力してください',
        ];
    }

    /**
     * バリデーション成功後に認証チェックを行う
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            // 入力値を取得
            $credentials = $this->only('email', 'password');

            // 認証失敗ならエラーを追加
            if (!Auth::guard('staff')->attempt($credentials)) {
                $validator->errors()->add('login', 'ログイン情報が登録されていません。');
            }
        });
    }
}
